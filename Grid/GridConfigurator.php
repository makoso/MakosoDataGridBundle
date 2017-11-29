<?php
/**
 * Created by Krzysztof Makowski <kontakt@krzysztof-makowski.pl>.
 * Project: EMagic
 * Date: 28.11.17
 * Time: 20:13
 */

namespace Makoso\DataGridBundle\Grid;


use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\QueryBuilder;

abstract class GridConfigurator implements GridConfiguratorInterface
{
    protected $actionColumns;
    protected $columns;
    protected $name;
    protected $entityClass;
    protected $actionColumnOnLeft = false;
    protected $perPage = 5;

    public function __construct()
    {
        $this->actionColumns = new ArrayCollection();
        $this->columns = new ArrayCollection();
    }

    public function getActionColumns():ArrayCollection
    {
        return $this->actionColumns;
    }

    public function getColumns():ArrayCollection
    {
        return $this->columns;
    }

    public function getActionColumnOnLeft():bool
    {
        return $this->actionColumnOnLeft;
    }

    /**
     * @return string Entity class
     * Entity class
     * @throws \ErrorException
     */
    public function getEntityClass():string
    {
        if(!$this->entityClass){
            throw new \ErrorException('entityClass must be provided');
        }
        return $this->entityClass;
    }

    /**
     * @return string
     * @throws \ErrorException
     */
    public function getName():string
    {
        if(!$this->name){
            throw new \ErrorException('name must be provided');
        }
        return $this->name;
    }

    public function titleFormatting(string $title):string
    {
        return $title;
    }

    public function getPerPage():int
    {
        return $this->perPage;
    }

    public function manipulateQuery(QueryBuilder $queryBuilder):void{}
}