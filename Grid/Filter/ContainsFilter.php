<?php
/**
 * Created by Krzysztof Makowski <kontakt@krzysztof-makowski.pl>.
 * Project: EMagic
 * Date: 29.11.17
 * Time: 00:17
 */

namespace Makoso\DatagridBundle\Grid\Filter;

use Makoso\DatagridBundle\Grid\Column\GridColumn;
use Makoso\DatagridBundle\Grid\Grid;
use Doctrine\ORM\QueryBuilder;

class ContainsFilter implements FilterInterface
{
    public function filter(QueryBuilder $queryBuilder, GridColumn $gridColumn, Grid $grid):void
    {
        $value = $gridColumn->getFilterableValue()['value'];
        if(!empty($value)){
            $parameterKey = $grid->getNextBindNumber();
            if($queryBuilder->getDQLParts()['where'] == null){
                $queryBuilder->where($gridColumn->getSelect().' LIKE ?'.$parameterKey);
            } else {
                $queryBuilder->andWhere($gridColumn->getSelect().' LIKE ?'.$parameterKey);
            }
            $queryBuilder->setParameter($parameterKey, '%'.$value.'%');
        }
    }

    public function needSecondInput():bool
    {
        return false;
    }

    public function getDisplayName():string
    {
        return 'Contains';
    }
}