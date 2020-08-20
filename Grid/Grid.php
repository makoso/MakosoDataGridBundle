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
use Symfony\Component\HttpFoundation\JsonResponse;
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

    private $executed = false;

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
        $this->resetObject();
        $this->config = $configurator;

        return clone $this;
    }

    private function execute()
    {
        if (!$this->executed) {
            $this->init();
            $this->buildBaseQuery();

            $this->totalRecords = $this->doQueryForTotalRecords();
            $this->totalPages   = ceil($this->totalRecords / $this->config->getPerPage());

            $this->currentPage = $this->formData['page'] ?? 1;

            if ($this->currentPage > $this->totalPages) {
                $this->currentPage = 1;
            }

            $this->qb->setFirstResult(
                abs(($this->currentPage - 1) * $this->config->getPerPage())
            );

            $this->qb->setMaxResults($this->config->getPerPage());

            $this->gridModel->setData($this->qb->getQuery()->getArrayResult());
            $this->gridModel->setRecordsTotal($this->gridModel->getRecordsFiltered());

            $this->executed = true;
        }
    }

    /**
     * @return Grid
     */
    public function getGrid()
    {
        $this->execute();

        return $this;
    }

    /**
     * @return JsonResponse
     */
    public function getJsonResponse():JsonResponse
    {
        $this->execute();

        $jsonObject              = new \stdClass();
        $jsonObject->formKey     = $this->config->getName();
        $jsonObject->totalPages  = $this->totalPages;
        $jsonObject->currnetPage = $this->currentPage;
        $jsonObject->data        = $this->getGridModel()->getData();

        return new JsonResponse($jsonObject);
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
                if (!$column->getFilterGroup()) {
                    $column->setFilterable(false);
                    continue;
                }

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

                if ($filterData['filterType'] instanceof FilterInterface && (!empty($filterData['value']) || !empty($filterData['value2']))) {
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

    private function resetObject()
    {
        $this->classMetadata = null;
        $this->qb            = null;
        $this->gridModel     = null;
        $this->formBuilder   = null;
        $this->formObject    = null;
        $this->form          = null;
        $this->formData      = [];
        $this->totalRecords  = 0;
        $this->totalPages    = 0;
        $this->currentPage   = 1;
        $this->executed      = false;
    }

    private function buildBaseQuery()
    {
        if ($this->config->getQueryBuilder() === null) {
            $this->qb->from($this->config->getEntityClass(), self::GRID_QUERY_ALIAS);

            foreach ($this->config->getColumns() as $column) {
                $this->qb->addSelect($column->getSelect().' '.$column->getName());
            }
        } else {
            $this->qb = $this->config->getQueryBuilder();
        }

        $this->config->manipulateQuery($this->qb);

        if ($this->formObject->isSubmitted() && $this->formObject->isValid()) {
            $this->processSort();
            $this->processFilter();
        }
    }

    /**
     * @return int
     */
    private function doQueryForTotalRecords():int
    {
        if ($this->config->getQueryBuilder() === null) {
            $qb = clone $this->qb;
            $qb->resetDQLPart('select');
            $qb->resetDQLPart('orderBy');
            return (int)$qb->select('COUNT('.self::GRID_QUERY_ALIAS.')')->getQuery(
            )->getSingleScalarResult();
        } else {
            $qb = clone $this->config->getQueryBuilder();
            $qb->resetDQLPart('select');
            $qb->resetDQLPart('orderBy');
            return (int)$qb->select('COUNT('.$this->config->getRootAlias().')')->getQuery(
            )->getSingleScalarResult();
        }
    }
}