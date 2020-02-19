<?php
/**
 * Created by Krzysztof Makowski <kontakt@krzysztof-makowski.pl>.
 * Project: EMagic
 * Date: 29.11.17
 * Time: 13:10
 */

namespace Makoso\DatagridBundle\Grid\Filter;

use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class FilterGroup implements FilterGroupInterface
{
    protected $filters;

    protected $firstInputType = TextType::class;
    protected $secondInputType = TextType::class;

    protected $firstInputOptions = [
        'label' => false,
        'attr' => [
            'class' => 'input-1',
        ],
        'required' => false,
    ];

    protected $secondInputOptions = [
        'label' => false,
        'attr' => [
            'class' => 'input-2',
        ],
        'required' => false,
    ];

    function __construct()
    {
        $this->filters = new ArrayCollection();
    }

    public function getFilters(): ArrayCollection
    {
        return $this->filters;
    }

    public function getFirstInputType(): string
    {
        return $this->firstInputType;
    }

    public function setFirstInputType(string $firstInputType): void
    {
        $this->firstInputType = $firstInputType;
    }

    public function getSecondInputType(): string
    {
        return $this->secondInputType;
    }

    public function setSecondInputType(string $secondInputType): void
    {
        $this->secondInputType = $secondInputType;
    }

    public function getFirstInputOptions(): array
    {
        return $this->firstInputOptions;
    }

    public function setFirstInputOptions(array $firstInputOptions): void
    {
        $this->firstInputOptions = $firstInputOptions;
    }

    public function getSecondInputOptions(): array
    {
        return $this->secondInputOptions;
    }

    public function setSecondInputOptions(array $secondInputOptions): void
    {
        $this->secondInputOptions = $secondInputOptions;
    }

}