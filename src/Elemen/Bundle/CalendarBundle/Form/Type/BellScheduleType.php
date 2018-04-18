<?php

namespace Elemen\Bundle\CalendarBundle\Form\Type;

use Elemen\Bundle\CoreBundle\Validator\Constraints\CollectionUniqueItem;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;



class BellScheduleType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(
                'name',
                'text',
                [
                    'label'    => 'elemen.calendar.bell_schedule.name.label',
                    'tooltip' =>  'elemen.calendar.bell_schedule.name.description',
                    'required' => true,
                    'constraints' => [
                        new Assert\NotBlank()
                    ],
                ]
            )
            ->add(
                'timetables',
                TimetableCollectionType::class,
                [
                    'label'    => '',
                    'required' => false,
                    'type'=> 'elemen_timetable',
                    'show_form_when_empty' => true,
                    'error_bubbling' => true,
                    'options'  => [
                        'data_class' => 'Elemen\Bundle\CalendarBundle\Entity\Timetable',
                    ],
                    'constraints' => [
                        new CollectionUniqueItem([
                            'fields' => [
                                [
                                    'name' => 'period',
                                    'property' => 'id'
                                ],
                                [
                                    'name' => 'endTime',
                                    'property' => 'timestamp'
                                ],
                            ]
                        ])
                    ],
                ]
            )
        ;


    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            array(
                'data_class'           => 'Elemen\Bundle\CalendarBundle\Entity\BellSchedule',
                'ownership_disabled'   => true
            )
        );
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return $this->getBlockPrefix();
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'elemen_bell_schedule';
    }
}
