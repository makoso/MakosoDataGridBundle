<?php

namespace Makoso\DatagridBundle\Grid\Filter;

use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class SelectFilterGroup extends FilterGroup
{
    public function __construct(array $choiceTypeOptions)
    {
        parent::__construct();
        $this->getFilters()->add(new EqualFilter());
        $this->getFilters()->add(new NotEqualFilter());

        $this->setFirstInputType(ChoiceType::class);
        $this->setFirstInputOptions($choiceTypeOptions);
    }
}