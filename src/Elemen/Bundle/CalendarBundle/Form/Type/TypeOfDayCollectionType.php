<?php

namespace Elemen\Bundle\CalendarBundle\Form\Type;

use Elemen\Bundle\CoreBundle\Validator\Constraints\CollectionUniqueItem;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TypeOfDayCollectionType extends AbstractType
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
                'type'           => TypeOfDayType::class,
                'item_data_class'    => 'Elemen\Bundle\CalendarBundle\Entity\TypeOfDay',
                'table_columns' => [
                    'priority' => [ 'label' => 'elemen.calendar.period.priority.label' ],
                    'name' => [
                        'label' => 'elemen.calendar.period.name.label',
                        'tooltip' =>'elemen.calendar.period.name.description'
                    ]
                ],
                'constraints' => [
                    new CollectionUniqueItem([
                        'fields' => [
                            [
                                'name' => 'name'
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
        return 'elemen_type_of_day_collection';
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return $this->getBlockPrefix();
    }

}
