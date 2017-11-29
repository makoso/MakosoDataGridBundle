<?php
/**
 * Created by Krzysztof Makowski <kontakt@krzysztof-makowski.pl>.
 * Project: EMagic
 * Date: 29.11.17
 * Time: 13:10
 */

namespace Makoso\DatagridBundle\Grid\Filter;


use Doctrine\Common\Collections\ArrayCollection;

class FilterGroup implements FilterGroupInterface
{
    protected $filters;

    function __construct()
    {
        $this->filters = new ArrayCollection();
    }

    public function getFilters():ArrayCollection
    {
        return $this->filters;
    }
}