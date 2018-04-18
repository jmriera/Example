<?php

namespace Elemen\Bundle\CalendarBundle\Form\Type;

use Elemen\Bundle\CalendarBundle\Entity\Period;
use Elemen\Bundle\CalendarBundle\Entity\Schedule;
use Elemen\Bundle\CalendarBundle\Form\DataTransformer\ScheduleTransformer;
use Elemen\Bundle\CalendarBundle\Form\DataTransformer\ScheduleViewTransformer;
use Oro\Bundle\EntityBundle\ORM\DoctrineHelper;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PeriodDayChoiceType extends AbstractType
{
    /** @var DoctrineHelper */
    private $doctrineHelper;

    /**
     * PeriodDayChoiceType constructor.
     * @param DoctrineHelper $doctrineHelper
     */
    public function __construct(DoctrineHelper $doctrineHelper)
    {
        $this->doctrineHelper = $doctrineHelper;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {

    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            [
                'label'    => 'elemen.course.schedule.form.select.label',
                'tooltip' =>  'elemen.course.schedule.form.select.description',
                'class'    => 'Elemen\Bundle\CalendarBundle\Entity\Schedule',
                'multiple' => true,
                'expanded' => true,
                'required' => true,
                'translatable_options' => false,
                'choice_value' => function (Schedule $schedule) {
                    return $schedule->getPeriod()->getId() . ';' . $schedule->getDay()->getId();
                },
                'choice_label' => function ($schedule) {
                    if(!$schedule instanceof  Schedule) return $schedule;
                    return $schedule->getPeriod()->getId() . ';' . $schedule->getDay()->getId();
                },


            ]
        );
    }


    public function getParent()
    {
        return 'entity';
    }

    public function getName()
    {
        $this->getBlockPrefix();
    }

    public function getBlockPrefix()
    {
        return 'elemen_period_day_choice';
    }
}
