<?php
/**
 * Created by Krzysztof Makowski <kontakt@krzysztof-makowski.pl>.
 * Project: EMagic
 * Date: 29.11.17
 * Time: 00:15
 */

namespace Makoso\DatagridBundle\Grid\Filter;


use App\Grid\Column\GridColumn;
use App\Grid\Grid;
use Doctrine\ORM\QueryBuilder;

interface FilterInterface
{
    public function filter(QueryBuilder $queryBuilder, GridColumn $gridColumn, Grid $grid):void;
    public function getDisplayName():string;
    public function needSecondInput():bool;
}