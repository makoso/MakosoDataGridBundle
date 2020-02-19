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

class Between implements FilterInterface
{
    public function filter(QueryBuilder $queryBuilder, GridColumn $gridColumn, Grid $grid): void
    {
        $value = $gridColumn->getFilterableValue()['value'];
        $value2 = $gridColumn->getFilterableValue()['value'];
        if ((!empty($value) || $value === 0) && (!empty($value2) || $value2 === 0) && (is_numeric($value) && is_numeric($value2))) {
            $exp1 = $queryBuilder->expr()->gte($gridColumn->getSelect(), (float)$value);
            $exp2 = $queryBuilder->expr()->lte($gridColumn->getSelect(), (float)$value2);
            if ($queryBuilder->getDQLParts()['where'] == null) {
                $queryBuilder->where($exp1);
            } else {
                $queryBuilder->andWhere($exp1);
            }
            $queryBuilder->andWhere($exp2);
        }
    }

    public function getDisplayName(): string
    {
        return 'Between';
    }

    public function needSecondInput(): bool
    {
        return true;
    }
}