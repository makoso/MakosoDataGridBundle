<?php
/**
 * Created by Krzysztof Makowski <kontakt@krzysztof-makowski.pl>.
 * Project: EMagic
 * Date: 29.11.17
 * Time: 13:09
 */

namespace Makoso\DatagridBundle\Grid\Filter;


use Doctrine\Common\Collections\ArrayCollection;

interface FilterGroupInterface
{
    public function getFilters():ArrayCollection;
}