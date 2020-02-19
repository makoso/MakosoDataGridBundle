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

class GreaterThanOrEqual implements FilterInterface
{
    public function filter(QueryBuilder $queryBuilder, GridColumn $gridColumn, Grid $grid): void
    {
        $value = $gridColumn->getFilterableValue()['value'];
        if ((!empty($value) || $value === 0) && is_numeric($value)) {
            $exp = $queryBuilder->expr()->gte($gridColumn->getSelect(), (float)$value);
            if ($queryBuilder->getDQLParts()['where'] == null) {
                $queryBuilder->where($exp);
            } else {
                $queryBuilder->andWhere($exp);
            }
        }
    }

    public function getDisplayName(): string
    {
        return 'GreaterThanOrEqual';
    }

    public function needSecondInput(): bool
    {
        return false;
    }
}