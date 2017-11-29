<?php
/**
 * Created by Krzysztof Makowski <kontakt@krzysztof-makowski.pl>.
 * Project: EMagic
 * Date: 28.11.17
 * Time: 23:27
 */

namespace Makoso\DataGridBundle\Form\Type;

use Makoso\DataGridBundle\Grid\Filter\FilterGroupInterface;
use Makoso\DataGridBundle\Grid\Filter\FilterInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class FilterableType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        /** @var FilterGroupInterface $filterGroup */
        $filterGroup = $options['filter'];
        $builder
            ->add('filterType', ChoiceType::class,[
                'label' => false,
                'choices' => $filterGroup->getFilters(),
                'choice_label' => function(FilterInterface $filter){
                    return $filter->getDisplayName();
                },
                'choice_attr' => function(FilterInterface $filter){
                    return [
                        'data-second-input' => $filter->needSecondInput() ? 'true' : 'false'
                    ];
                },
                'required' => false,
                'placeholder' => 'Enable filtering'
            ])
            ->add('value', TextType::class, [
                'label' => false,
                'attr' => [
                    'class' => 'input-1'
                ],
                'required' => false
            ])
            ->add('value2', TextType::class, [
                'label' => false,
                'attr' => [
                    'class' => 'input-1'
                ],
                'required' => false
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'label' => false,
            'filter' => null
        ));
    }
}