<?php
/**
 * Created by Krzysztof Makowski <kontakt@krzysztof-makowski.pl>.
 * Project: EMagic
 * Date: 28.11.17
 * Time: 12:31
 */

namespace Makoso\DatagridBundle\Grid;

class GridModel
{
    /** @var  integer */
    protected $recordsTotal;
    /** @var  integer */
    protected $recordsFiltered;
    /** @var  array */
    protected $data = [];
    /** @var  Grid */
    protected $grid;

    /**
     * @return int
     */
    public function getRecordsTotal():int
    {
        return $this->recordsTotal;
    }

    /**
     * @param int $recordsTotal
     *
     * @return GridModel
     */
    public function setRecordsTotal(int $recordsTotal):GridModel
    {
        $this->recordsTotal = $recordsTotal;

        return $this;
    }

    /**
     * @return int
     */
    public function getRecordsFiltered():int
    {
        return $this->recordsFiltered;
    }

    /**
     * @return array
     */
    public function getData():array
    {
        return $this->data;
    }

    /**
     * @param array $data
     *
     * @return GridModel
     */
    public function setData(array $data):GridModel
    {
        $this->data            = $data;
        $this->recordsFiltered = count($data);

        return $this;
    }

    /**
     * @return Grid
     */
    public function getGrid():Grid
    {
        return $this->grid;
    }

    /**
     * @param Grid $grid
     *
     * @return GridModel
     */
    public function setGrid(Grid $grid):GridModel
    {
        $this->grid = $grid;

        return $this;
    }


}