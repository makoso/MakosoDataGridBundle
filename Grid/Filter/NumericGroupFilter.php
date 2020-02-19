<?php
/**
 * Created by Krzysztof Makowski <kontakt@krzysztof-makowski.pl>.
 * Project: EMagic
 * Date: 29.11.17
 * Time: 13:10
 */

namespace Makoso\DatagridBundle\Grid\Filter;

use Symfony\Component\Form\Extension\Core\Type\NumberType;

class NumericGroupFilter extends FilterGroup
{
    protected $firstInputType = NumberType::class;
    protected $secondInputType = NumberType::class;

    protected $firstInputOptions = [
        'label' => false,
        'attr' => [
            'class' => 'input-1',
        ],
        'required' => false,
        'html5' => true,
    ];

    protected $secondInputOptions = [
        'label' => false,
        'attr' => [
            'class' => 'input-2',
        ],
        'required' => false,
        'html5' => true,
    ];

    public function __construct()
    {
        parent::__construct();
        $this->filters->add(new EqualFilter());
        $this->filters->add(new NotEqualFilter());
        $this->filters->add(new Between());
        $this->filters->add(new GreaterThan());
        $this->filters->add(new GreaterThanOrEqual());
        $this->filters->add(new LessThan());
        $this->filters->add(new LessThanOrEqual());
    }

}