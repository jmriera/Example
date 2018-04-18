<?php

namespace Elemen\Bundle\CalendarBundle\Form\Type;

use Doctrine\ORM\EntityRepository;
use Elemen\Bundle\CalendarBundle\Entity\Manager\SystemCalendarManager;
use Elemen\Bundle\CalendarBundle\Entity\Repository\PeriodRepository;
use Elemen\Bundle\CalendarBundle\Entity\SystemCalendarAwareTrait;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;


class TimetableType extends AbstractType
{
    /** @var SystemCalendarManager */
    protected $systemCalendarManager;

    /**
     * TimetableType constructor.
     * @param SystemCalendarManager $systemCalendarManager
     */
    public function __construct(SystemCalendarManager $systemCalendarManager)
    {
        $this->systemCalendarManager = $systemCalendarManager;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $contextCalendarId = $this->systemCalendarManager->getCalendarContext()->getId();

        $builder
            ->add('id', 'hidden')
            ->add(
                'period',
                'entity',
                [
                    'label'    => 'elemen.calendar.period.entity_label',
                    'tooltip' =>  'elemen.calendar.period.entity_description',
                    'required'    => true,
                    'class'       => 'Elemen\Bundle\CalendarBundle\Entity\Period',
                    'query_builder' => function (PeriodRepository $repository) use ($contextCalendarId) {
                        return $repository->getPeriodsByCalendarIdQueryBuilder($contextCalendarId);
                    },
                    'property'    => 'shortName',
                    'attr' => ['style' => 'width: 40px']
                ]
            )
            ->add(
                'startTime',
                'time',
                [
                    'label'    => 'elemen.calendar.timetable.start_time.label',
                    'tooltip' =>  'elemen.calendar.timetable.start_time.description',
                    'required' => true,
                    'widget' => 'single_text',
                    'constraints' => [
                        new Assert\NotBlank()
                    ],
                    'attr' => ['style' => 'width: 70px']
                ]
            )
            ->add(
                'endTime',
                'time',
                [
                    'label'    => 'elemen.calendar.timetable.end_time.label',
                    'tooltip' =>  'elemen.calendar.timetable.end_time.description',
                    'required' => true,
                    'widget' => 'single_text',
                    'constraints' => [
                        new Assert\NotBlank()
                    ],
                    'attr' => ['style' => 'width: 70px']
                ]
            )
        ;

    }


    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => 'Elemen\Bundle\CalendarBundle\Entity\Timetable',
            'intention' => 'timetable'
        ]);
    }

    public function getBlockPrefix()
    {
        return 'elemen_timetable';
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return $this->getBlockPrefix();
    }
}
