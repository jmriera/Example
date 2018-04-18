<?php

namespace Elemen\Bundle\CalendarBundle\Form\Type;

use Elemen\Bundle\CalendarBundle\Entity\BellSchedule;
use Elemen\Bundle\CalendarBundle\Entity\CycleDay;
use Elemen\Bundle\CalendarBundle\Entity\TypeOfDay;
use Elemen\Bundle\SecurityBundle\Authentication\TokenAccessor;
use Oro\Bundle\CalendarBundle\Model\Recurrence;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;

class CalendarConfigType extends AbstractType
{
    /** @var Recurrence  */
    protected $recurrenceModel;
    /** @var TokenAccessor $tokenAccessor */
    protected $tokenAccessor;

    /**
     * RecurrenceFormType constructor.
     *
     * @param Recurrence $recurrenceModel
     * @param $tokenAccessor
     */
    public function __construct(Recurrence $recurrenceModel, $tokenAccessor)
    {
        $this->recurrenceModel = $recurrenceModel;
        $this->tokenAccessor = $tokenAccessor;

    }
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(
                'startDate',
                'oro_date',
                [
                    'label'     => 'elemen.calendar.config.start_date.label',
                    'tooltip'   =>  'elemen.calendar.config.start_date.description',
                    'minDate'   =>  'today +1',
                    'required'  => true
                ]
            )
            ->add(
                'endDate',
                'oro_date',
                [
                    'label'    => 'elemen.calendar.config.end_date.label',
                    'tooltip' =>  'elemen.calendar.config.end_date.description',
                    'minDate'   =>  'today +1',
                    'required' => true
                ]
            )
            ->add(
                'dayOfWeek',
                'choice',
                [
                    'label' => 'elemen.calendar.config.day_of_week.label',
                    'tooltip' =>  'elemen.calendar.config.day_of_week.description',
                    'required' => false,
                    'multiple' => true,
                    'expanded' => true,
                    'choices' => $this->recurrenceModel->getDaysOfWeek()
                ]
            )
            ->add(
                'inSession',
                'checkbox',
                [
                    'label' => 'elemen.calendar.config.in_session.label',
                    'tooltip' =>  'elemen.calendar.config.in_session.description',
                    'required' => false
                ]
            )
            ->add(
                'cycleDay',
                'entity',
                [
                    'label' => 'elemen.calendar.cycle_day.entity_label',
                    'tooltip' =>  'elemen.calendar.cycle_day.entity_description',
                    'class' => CycleDay::class,
                    'choice_label' => 'name',
                    'required' => false,
                ]
            )
            ->add(
                'bellSchedule',
                'entity',
                [
                    'label'    => 'elemen.calendar.bell_schedule.entity_label',
                    'tooltip' =>  'elemen.calendar.bell_schedule.entity_description',
                    'class' => BellSchedule::class,
                    'choice_label' => 'name',
                    'required' => false,
                ]
            )
            ->add(
                'attendanceValue',
                'number',
                [
                    'label' => 'elemen.calendar.config.attendance_value.label',
                    'tooltip' =>  'elemen.calendar.config.attendance_value.description',
                    'required' => false
                ]
            )
            ->add(
                'typeOfDay',
                'entity',
                [
                    'label'    => 'elemen.calendar.type_of_day.entity_label',
                    'tooltip' =>  'elemen.calendar.type_of_day.entity_description',
                    'class' => TypeOfDay::class,
                    'choice_label' => 'name',
                    'required' => false,

                ]
            )
            ->add(
                'note',
                'textarea',
                [
                    'label' => 'elemen.calendar.config.note.label',
                    'tooltip' =>  'elemen.calendar.config.note.description',
                    'required' => false
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
                'mapped'               => false,
                'data_class'           => null
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
        return 'elemen_calendar_config';
    }
}
