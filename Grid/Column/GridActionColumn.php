<?php
/**
 * Created by Krzysztof Makowski <kontakt@krzysztof-makowski.pl>.
 * Project: EMagic
 * Date: 28.11.17
 * Time: 14:52
 */

namespace Makoso\DatagridBundle\Grid\Column;

class GridActionColumn
{
    /** @var  string */
    protected $name;
    /** @var  string */
    protected $routeName;
    /** @var  array */
    protected $routeParametersMapping = [];
    /** @var  string */
    protected $cssClass;
    /** @var  string */
    protected $title;
    /** @var callable|null */
    protected $renderCondition;

    public function __construct(
        ?string $name,
        ?string $routeName,
        ?array $routeParametersMapping,
        ?string $title = null,
        ?string $cssClass = null,
        ?callable $renderCondition = null
    ) {
        $this->name                   = $name;
        $this->routeName              = $routeName;
        $this->routeParametersMapping = $routeParametersMapping;
        $this->title                  = $title;
        $this->cssClass               = $cssClass;
        $this->renderCondition        = $renderCondition;
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
     * @return GridActionColumn
     */
    public function setName(string $name):GridActionColumn
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return string
     */
    public function getRouteName():string
    {
        return $this->routeName;
    }

    /**
     * @param string $routeName
     *
     * @return GridActionColumn
     */
    public function setRouteName(string $routeName):GridActionColumn
    {
        $this->routeName = $routeName;

        return $this;
    }

    /**
     * @return array
     */
    public function getRouteParametersMapping():array
    {
        return $this->routeParametersMapping;
    }

    /**
     * @param array $routeParametersMapping
     *
     * @return GridActionColumn
     */
    public function setRouteParametersMapping(array $routeParametersMapping):GridActionColumn
    {
        $this->routeParametersMapping = $routeParametersMapping;

        return $this;
    }

    /**
     * @return string
     */
    public function getCssClass():string
    {
        return $this->cssClass;
    }

    /**
     * @param string $cssClass
     *
     * @return GridActionColumn
     */
    public function setCssClass(string $cssClass):GridActionColumn
    {
        $this->cssClass = $cssClass;

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
     * @return GridActionColumn
     */
    public function setTitle(string $title):GridActionColumn
    {
        $this->title = $title;

        return $this;
    }

    public function getRenderCondition(): ?callable
    {
        return $this->renderCondition;
    }

    public function setRenderCondition(?callable $renderCondition): GridActionColumn
    {
        $this->renderCondition = $renderCondition;

        return $this;
    }

}