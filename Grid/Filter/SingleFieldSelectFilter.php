<?php

declare(strict_types=1);

namespace Makoso\DatagridBundle\Grid\Filter;

use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

final class SingleFieldSelectFilter extends FilterGroup
{
    public function __construct(array $choiceTypeOptions = [])
    {
        parent::__construct();
        $this->getFilters()->add(new EqualFilter());

        $this->setFirstInputType(ChoiceType::class);
        $this->setFirstInputOptions($choiceTypeOptions);
    }

}
