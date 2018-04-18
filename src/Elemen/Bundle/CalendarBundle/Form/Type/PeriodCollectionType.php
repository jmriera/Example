<?php

namespace Elemen\Bundle\CalendarBundle\Form\Type;

use Elemen\Bundle\CoreBundle\Validator\Constraints\CollectionUniqueItem;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Validator\Constraints as Assert;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PeriodCollectionType extends AbstractType
{
    /**
     * {@inheritdoc}
     *
     * @SuppressWarnings(PHPMD.NPathComplexity)
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            [
                'type'           => PeriodType::class,
                'allow_add'     => false,
                'item_data_class'    => 'Elemen\Bundle\CalendarBundle\Entity\Period',
                'table_columns' => [
                    'priority' => [ 'label' => 'elemen.calendar.period.priority.label' ],
                    'name' => [
                        'label' => 'elemen.calendar.period.name.label',
                        'tooltip' =>'elemen.calendar.period.name.description'
                    ],
                    'shortName' => [
                        'label' => 'elemen.calendar.period.short_name.label',
                        'tooltip' =>'elemen.calendar.period.short_name.description'
                    ]
                ],
                'constraints' => [
                    new CollectionUniqueItem([
                        'fields' => [
                            [
                                'name' => 'shortName'
                            ]
                        ]
                    ])
                ]

            ]
        );
    }

    /**
     * {@inheritdoc}
     */
    public function getParent()
    {
        return 'elemen_collection';
    }

    public function getBlockPrefix()
    {
        return 'elemen_period_collection';
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return $this->getBlockPrefix();
    }

}
