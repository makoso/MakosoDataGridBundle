<?php
/**
 * Created by Krzysztof Makowski <kontakt@krzysztof-makowski.pl>.
 * Project: EMagic
 * Date: 28.11.17
 * Time: 20:13
 */

namespace Makoso\DataGridBundle\Grid;


use App\Grid\Column\GridActionColumn;
use App\Grid\Column\GridColumn;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\QueryBuilder;

interface GridConfiguratorInterface
{
    /**
     * @return string
     * Name Of grid, used for setting form name it allows use multiple grid.
     * This option must by configured for each grid
     */
    public function getName():string;

    /**
     * @return bool
     * If Your grid contains actions, You can change they place to left
     */
    public function getActionColumnOnLeft():bool;

    /**
     * @return GridActionColumn[]|ArrayCollection
     * ArrayCollection of actions
     */
    public function getActionColumns():ArrayCollection;

    /**
     * @return GridColumn[]|ArrayCollection
     * ArrayCollection of columns
     */
    public function getColumns():ArrayCollection;

    /**
     * @return string
     * Entity class
     */
    public function getEntityClass():string;

    /**
     * @return int
     * records per page
     */
    public function getPerPage():int;

    /**
     * @param string $title
     *
     * @return string Entity class
     * can format title column next this value is translated
     */
    public function titleFormatting(string $title):string;

    public function manipulateQuery(QueryBuilder $queryBuilder):void;
}