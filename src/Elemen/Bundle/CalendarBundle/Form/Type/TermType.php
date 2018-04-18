<?php

namespace Elemen\Bundle\CalendarBundle\Form\Type;

use Elemen\Bundle\CalendarBundle\Entity\Term;
use Elemen\Bundle\CalendarBundle\Form\Type\SystemCalendarSelectType;
use Oro\Bundle\FormBundle\Form\Type\OroChoiceType;
use Oro\Bundle\FormBundle\Form\Type\OroDateType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Validator\Constraints\NotBlank;


class TermType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        $builder
            ->add(
                'systemCalendar',
                SystemCalendarSelectType::class,
                [
                    'label'    => 'elemen.calendar.term.form.choose_calendar.label',
                    'tooltip' =>  'elemen.calendar.term.form.choose_calendar.description',
                    'required' => true,
                    'constraints' => [new NotBlank()],
                    'error_bubbling' => false,
                ]
            )
            ->add(
                'name',
                TextType::class,
                [
                    'label'    => 'elemen.calendar.term.name.label',
                    'tooltip' =>  'elemen.calendar.term.name.description',
                    'required' => true
                ]
            )
            ->add(
                'shortName',
                TextType::class,
                [
                    'label'    => 'elemen.calendar.term.short_name.label',
                    'tooltip' =>  'elemen.calendar.term.short_name.description',
                    'required' => true,
                    'max_length' => 2
                ]
            )
            ->add(
                'division',
                OroChoiceType::class,
                [
                    'label'     => 'elemen.calendar.term.division.label',
                    'tooltip'   => 'elemen.calendar.term.division.description',
                    'required'  => true,
                    'choices'   => Term::getYearDivisions()
                ]
            )
            ->add(
                'firstDay',
                OroDateType::class,
                [
                    'label'    => 'elemen.calendar.term.first_day.label',
                    'tooltip' =>  'elemen.calendar.term.first_day.description',
                    'required' => true
                ]
            )
            ->add(
                'lastDay',
                OroDateType::class,
                [
                    'label'    => 'elemen.calendar.term.last_day.label',
                    'tooltip' =>  'elemen.calendar.term.last_day.description',
                    'required' => true
                ]
            )
        ;


    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            array(
                'data_class'           => 'Elemen\Bundle\CalendarBundle\Entity\Term',
                'intention'            => 'term',
                'ownership_disabled'   => true,
                'cascade_validation'   => true
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
        return 'elemen_term';
    }
}
