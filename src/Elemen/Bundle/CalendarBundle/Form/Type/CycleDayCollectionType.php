<?php

namespace Elemen\Bundle\CalendarBundle\Form\Type;

use Elemen\Bundle\CoreBundle\Validator\Constraints\CollectionUniqueItem;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CycleDayCollectionType extends AbstractType
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
                'type'           => CycleDayType::class,
                'allow_add'     => false,
                'item_data_class'    => 'Elemen\Bundle\CalendarBundle\Entity\CycleDay',
                'table_columns' => [
                    'priority' => [ 'label' => 'elemen.calendar.cycle_day.priority.label' ],
                    'name' => [
                        'label' => 'elemen.calendar.cycle_day.name.label',
                        'tooltip' =>'elemen.calendar.cycle_day.name.description'
                    ],
                    'shortName' => [
                        'label' => 'elemen.calendar.cycle_day.short_name.label',
                        'tooltip' =>'elemen.calendar.cycle_day.short_name.description'
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
        return 'elemen_cycle_day_collection';
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return $this->getBlockPrefix();
    }

}
