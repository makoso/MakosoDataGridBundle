<?php
/**
 * Created by Krzysztof Makowski <kontakt@krzysztof-makowski.pl>.
 * Project: EMagic
 * Date: 28.11.17
 * Time: 14:52
 */

namespace Makoso\DatagridBundle\Grid\Column;

use Makoso\DatagridBundle\Grid\Filter\FilterGroupInterface;

class GridColumn
{
    /** @var  string */
    protected $name;
    /** @var  string */
    protected $select;
    /** @var  string */
    protected $title;
    /** @var bool */
    protected $filterable = true;
    /** @var bool */
    protected $sortable = true;
    /** @var  FilterGroupInterface */
    protected $filterGroup;

    protected $sortableValue = '';
    /** @var  array|null */
    protected $filterableValue;

    public function __construct(
        string $name,
        string $select,
        ?string $title = null,
        bool $filterable = true,
        bool $sortable = true,
        ?FilterGroupInterface $filterGroup = null
    )
    {
        $this->name = $name;
        $this->select = $select;
        $this->title = $title ?? $name;
        $this->filterable = $filterable;
        $this->sortable = $sortable;
        $this->filterGroup = $filterGroup;
    }

    /**
     * @return string
     */
    public function getName():string
    {
        return $this->name;
    }

    /**
     * @param string $name
     *
     * @return GridColumn
     */
    public function setName(string $name):GridColumn
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return bool
     */
    public function isFilterable():bool
    {
        return $this->filterable;
    }

    /**
     * @param bool $filterable
     *
     * @return GridColumn
     */
    public function setFilterable(bool $filterable):GridColumn
    {
        $this->filterable = $filterable;

        return $this;
    }

    /**
     * @return bool
     */
    public function isSortable():bool
    {
        return $this->sortable;
    }

    /**
     * @param bool $sortable
     *
     * @return GridColumn
     */
    public function setSortable(bool $sortable):GridColumn
    {
        $this->sortable = $sortable;

        return $this;
    }

    /**
     * @return string
     */
    public function getSelect():string
    {
        return $this->select;
    }

    /**
     * @param string $select
     *
     * @return GridColumn
     */
    public function setSelect(string $select):GridColumn
    {
        $this->select = $select;

        return $this;
    }

    /**
     * @return string
     */
    public function getTitle():string
    {
        return $this->title;
    }

    /**
     * @param string $title
     *
     * @return GridColumn
     */
    public function setTitle(string $title):GridColumn
    {
        $this->title = $title;

        return $this;
    }

    /**
     * @return string
     */
    public function getSortableValue():string
    {
        return $this->sortableValue;
    }

    /**
     * @param string $sortableValue
     *
     * @return GridColumn
     */
    public function setSortableValue(string $sortableValue):GridColumn
    {
        $this->sortableValue = $sortableValue;

        return $this;
    }

    /**
     * @return array|null
     */
    public function getFilterableValue():?array
    {
        return $this->filterableValue;
    }

    /**
     * @param array|null $filterableValue
     *
     * @return GridColumn
     */
    public function setFilterableValue(?array $filterableValue):GridColumn
    {
        $this->filterableValue = $filterableValue;

        return $this;
    }

    /**
     * @return FilterGroupInterface
     */
    public function getFilterGroup():?FilterGroupInterface
    {
        return $this->filterGroup;
    }

    /**
     * @param FilterGroupInterface $filterGroup
     *
     * @return GridColumn
     */
    public function setFilterGroup(FilterGroupInterface $filterGroup):GridColumn
    {
        $this->filterGroup = $filterGroup;

        return $this;
    }


}