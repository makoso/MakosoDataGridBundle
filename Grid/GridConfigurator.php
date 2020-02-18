<?php
/**
 * Created by Krzysztof Makowski <kontakt@krzysztof-makowski.pl>.
 * Project: EMagic
 * Date: 28.11.17
 * Time: 20:13
 */

namespace Makoso\DatagridBundle\Grid;


use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\QueryBuilder;
use Makoso\DatagridBundle\Grid\Column\GridActionColumn;

abstract class GridConfigurator implements GridConfiguratorInterface
{
    protected $actionColumns;
    protected $columns;
    protected $name;
    protected $entityClass;
    protected $actionColumnOnLeft = false;
    protected $perPage = 5;
    protected $queryBuilder;
    protected $rootAlias = '_PLEASE_PROVIDE_ROOT_ENTITY_ALIAS_';

    public function __construct()
    {
        $this->actionColumns = new ArrayCollection();
        $this->columns = new ArrayCollection();
    }

    /** @deprecated */
    public function getActionColumns(): ArrayCollection
    {
        return $this->actionColumns;
    }

    public function getActionColumnsActive(array $row): ArrayCollection
    {
        return $this->actionColumns->filter(
            function (GridActionColumn $actionColumn) use ($row) {
                if (is_callable($actionColumn->getRenderCondition())) {
                    return $actionColumn->getRenderCondition()($row);
                }

                return true;
            }
        );
    }

    public function getColumns(): ArrayCollection
    {
        return $this->columns;
    }

    public function getActionColumnOnLeft(): bool
    {
        return $this->actionColumnOnLeft;
    }

    /**
     * @return string Entity class
     * Entity class
     * @throws \ErrorException
     */
    public function getEntityClass(): string
    {
        if (!$this->entityClass) {
            throw new \ErrorException('entityClass must be provided');
        }
        return $this->entityClass;
    }

    /**
     * @return string
     * @throws \ErrorException
     */
    public function getName(): string
    {
        if (!$this->name) {
            throw new \ErrorException('name must be provided');
        }
        return $this->name;
    }

    public function titleFormatting(string $title): string
    {
        return $title;
    }

    public function getPerPage(): int
    {
        return $this->perPage;
    }

    public function manipulateQuery(QueryBuilder $queryBuilder): void
    {
    }

    /**
     * @return QueryBuilder
     */
    public function getQueryBuilder(): ?QueryBuilder
    {
        return $this->queryBuilder;
    }

    /**
     * @return string
     */
    public function getRootAlias(): string
    {
        return $this->rootAlias;
    }

    /**
     * @param string $rootAlias
     *
     * @return GridConfigurator
     */
    public function setRootAlias(string $rootAlias): GridConfigurator
    {
        $this->rootAlias = $rootAlias;

        return $this;
    }

    /**
     * @param QueryBuilder $queryBuilder
     *
     * @return GridConfigurator
     */
    public function setQueryBuilder(QueryBuilder $queryBuilder)
    {
        $this->queryBuilder = $queryBuilder;

        return $this;
    }


}