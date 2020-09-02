<?php

declare(strict_types=1);

namespace Makoso\DatagridBundle\Grid\Filter;

final class SingleFieldTextFilter extends FilterGroup
{
    public function __construct(FilterInterface $filter)
    {
        parent::__construct();
        $this->filters->add($filter);
    }

}
