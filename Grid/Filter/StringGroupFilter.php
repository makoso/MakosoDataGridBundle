<?php
/**
 * Created by Krzysztof Makowski <kontakt@krzysztof-makowski.pl>.
 * Project: EMagic
 * Date: 29.11.17
 * Time: 13:10
 */

namespace Makoso\DatagridBundle\Grid\Filter;

class StringGroupFilter extends FilterGroup
{
    public function __construct()
    {
        parent::__construct();
        $this->filters->add(new ContainsFilter());
        $this->filters->add(new EqualFilter());
        $this->filters->add(new StartsWithFilter());
        $this->filters->add(new EndsWithFilter());
    }

}