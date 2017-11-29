<?php
/**
 * Created by Krzysztof Makowski <kontakt@krzysztof-makowski.pl>.
 * Project: EMagic
 * Date: 28.11.17
 * Time: 12:34
 */

namespace Makoso\DatagridBundle\Grid;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Mapping\ClassMetadata;
use Doctrine\ORM\QueryBuilder;
use Makoso\DatagridBundle\Form\Type\FilterableType;
use Makoso\DatagridBundle\Grid\Column\GridActionColumn;
use Makoso\DatagridBundle\Grid\Filter\FilterInterface;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormFactory;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Routing\Router;

class Grid
{
    const GRID_QUERY_ALIAS = '_grid';

    const GRID_SORT_ASC  = 'ASC';
    const GRID_SORT_DESC = 'DESC';
    const GRID_SORT_NONE = false;

    const GRID_FORM_SORT_KEY     = 'sort';
    const GRID_FORM_SORT_OPTIONS = [self::GRID_SORT_NONE, self::GRID_SORT_ASC, self::GRID_SORT_DESC];

    const GRID_FORM_FILTERABLE_KEY = 'filter';

    /** @var  EntityManagerInterface */
    private $em;

    /** @var  Request */
    private $request;

    /** @var GridConfiguratorInterface */
    private $config;

    /** @var QueryBuilder */
    private $qb;

    /** @var ClassMetadata */
    private $classMetadata;

    /** @var GridModel */
    private $gridModel;

    /** @var UrlGeneratorInterface */
    private $router;

    /** @var FormFactory */
    private $formFactory;

    /** @var FormBuilderInterface */
    private $formBuilder;

    /** @var FormView */
    private $form;

    /** @var FormInterface */
    private $formObject;

    /** @var array */
    private $formData = [];
    /** @var int */
    private $bindNumber = 0;
    /** @var bool */
    private $filtered = false;

    private $currentPage = 1;

    private $totalPages = 0;

    private $totalRecords = 0;

    public function __construct(
        EntityManagerInterface $entityManager,
        RequestStack $request,
        UrlGeneratorInterface $router,
        FormFactoryInterface $formFactory
    ) {
        $this->em          = $entityManager;
        $this->router      = $router;
        $this->request     = $request->getCurrentRequest();
        $this->formFactory = $formFactory;
    }

    public function init():void
    {
        $this->classMetadata = $this->em->getClassMetadata($this->config->getEntityClass());
        $this->qb            = $this->em->createQueryBuilder();
        $this->gridModel     = (new GridModel())->setGrid($this);

        $this->generateForm();
    }

    public function configure(GridConfiguratorInterface $configurator)
    {
        $this->config = $configurator;

        return $this;
    }

    /**
     * @return Grid
     */
    public function getGrid()
    {
        $this->init();
        $this->qb->from($this->config->getEntityClass(), self::GRID_QUERY_ALIAS);

        foreach ($this->config->getColumns() as $column) {
            $this->qb->addSelect($column->getSelect().' '.$column->getName());
        }
        $this->config->manipulateQuery($this->qb);

        if ($this->formObject->isSubmitted() && $this->formObject->isValid()) {
            $this->processSort();
            $this->processFilter();
        }

        $this->totalRecords = (int)(clone $this->qb)->select('COUNT('.self::GRID_QUERY_ALIAS.')')->getQuery(
        )->getSingleScalarResult();
        $this->totalPages = ceil($this->totalRecords / $this->config->getPerPage());

        $this->currentPage = $this->formData['page'] ?? 1;

        $this->qb->setFirstResult(
            abs(($this->currentPage - 1) * $this->config->getPerPage())
        );

        $this->qb->setMaxResults($this->config->getPerPage());

        $this->gridModel->setData($this->qb->getQuery()->getArrayResult());
        $this->gridModel->setRecordsTotal($this->gridModel->getRecordsFiltered());

        return $this;
    }

    public function generateActionLink(GridActionColumn $actionColumn, $row)
    {
        $mapping       = $actionColumn->getRouteParametersMapping();
        $mapParameters = array_flip($mapping);

        foreach ($mapping as $from => $to) {
            $mapParameters[$to] = $row[$from];
        }

        return $this->router->generate($actionColumn->getRouteName(), $mapParameters, Router::ABSOLUTE_URL);
    }

    public function generateForm()
    {
        $builder = $this->formFactory->createNamedBuilder(
            $this->config->getName(),
            FormType::class,
            null,
            ['label' => false]
        );

        $sortBuilder   = $this->formFactory->createNamedBuilder(
            self::GRID_FORM_SORT_KEY,
            FormType::class,
            null,
            ['label' => false]
        );
        $filterBuilder = $this->formFactory->createNamedBuilder(
            self::GRID_FORM_FILTERABLE_KEY,
            FormType::class,
            null,
            ['label' => false]
        );

        foreach ($this->config->getColumns() as $column) {
            /** Sort fields */
            if ($column->isSortable()) {
                $sortBuilder->add(
                    $column->getName(),
                    HiddenType::class
                );
            }
            /** Filter fields */
            if ($column->isFilterable()) {
                $filterBuilder->add(
                    $column->getName(),
                    FilterableType::class,
                    [
                        'label'  => false,
                        'filter' => $column->getFilterGroup(),
                    ]
                );
            }
        }

        $builder->add($sortBuilder);
        $builder->add($filterBuilder);

        $builder->add(
            'page',
            HiddenType::class,
            [
                'data' => 1,
            ]
        );

        $this->formBuilder = $builder;

        $this->formObject = $this->formBuilder->getForm();
        $this->formObject->handleRequest($this->request);
        $this->form     = $this->formObject->createView();
        $this->formData = $this->formObject->getData();
    }

    private function processSort()
    {
        foreach ($this->config->getColumns() as &$column) {
            /** Sort fields */
            if ($column->isSortable()) {
                switch ($this->formData[self::GRID_FORM_SORT_KEY][$column->getName()]) {
                    case(self::GRID_SORT_ASC):
                        $this->qb->addOrderBy($column->getSelect(), self::GRID_SORT_ASC);
                        $column->setSortableValue(self::GRID_SORT_ASC);
                        break;
                    case(self::GRID_SORT_DESC):
                        $this->qb->addOrderBy($column->getSelect(), self::GRID_SORT_DESC);
                        $column->setSortableValue(self::GRID_SORT_DESC);
                        break;
                }
            }
        }
    }

    private function processFilter()
    {
        foreach ($this->config->getColumns() as &$column) {
            /** filter fields */
            if ($column->isFilterable() && $column->getFilterGroup() != null) {
                $filterData = $this->formData[self::GRID_FORM_FILTERABLE_KEY][$column->getName()];

                if ($filterData['filterType'] instanceof FilterInterface) {
                    $column->setFilterableValue(
                        [
                            'value'  => $filterData['value'],
                            'value2' => $filterData['value2'],
                        ]
                    );
                    $filterData['filterType']->filter($this->qb, $column, $this);
                    $this->setFiltered();
                }
            }
        }
    }


    /**
     * @return GridConfiguratorInterface
     */
    public function getConfig():GridConfiguratorInterface
    {
        return $this->config;
    }

    /**
     * @return GridModel
     */
    public function getGridModel():GridModel
    {
        return $this->gridModel;
    }

    /**
     * @return FormView
     */
    public function getForm():FormView
    {
        return $this->form;
    }

    public function getNextBindNumber()
    {
        return ++$this->bindNumber;
    }

    public function setFiltered()
    {
        if (!$this->isFiltered()) {
            $this->filtered = true;
        }

        return $this;
    }

    /**
     * @return bool
     */
    public function isFiltered():bool
    {
        return $this->filtered;
    }

    /**
     * @return int
     */
    public function getTotalPages():int
    {
        return $this->totalPages;
    }

    /**
     * @return int
     */
    public function getTotalRecords():int
    {
        return $this->totalRecords;
    }

    /**
     * @return int
     */
    public function getCurrentPage():int
    {
        return $this->currentPage;
    }


}