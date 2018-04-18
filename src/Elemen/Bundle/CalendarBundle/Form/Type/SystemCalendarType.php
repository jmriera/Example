<?php

namespace Elemen\Bundle\CalendarBundle\Form\Type;


use Elemen\Bundle\CalendarBundle\Entity\Repository\CycleDayRepository;
use Elemen\Bundle\CalendarBundle\Entity\Repository\PeriodRepository;
use Oro\Bundle\CalendarBundle\Entity\SystemCalendar;
use Oro\Bundle\CalendarBundle\Provider\SystemCalendarConfig;
use Oro\Bundle\EntityBundle\ORM\DoctrineHelper;
use Oro\Bundle\FormBundle\Form\Type\OroDateType;
use Oro\Bundle\FormBundle\Utils\FormUtils;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Symfony\Component\Validator\Constraints as Assert;

class SystemCalendarType extends \Oro\Bundle\CalendarBundle\Form\Type\SystemCalendarType
{
    /** @var DoctrineHelper */
    private $doctrineHelper;

    public function __construct(AuthorizationCheckerInterface $authorizationChecker,
                                SystemCalendarConfig $calendarConfig,
                                DoctrineHelper $doctrineHelper
    )
    {
        parent::__construct($authorizationChecker, $calendarConfig);

        $this->doctrineHelper = $doctrineHelper;
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);

        $minYear = date_create('-2 year')->format('Y');
        $maxYear = date_create('+2 year')->format('Y');

        $builder
            ->add(
                'startDate',
                OroDateType::class,
                [
                    'required' => true,
                    'label'    => 'elemen.calendar.school_calendar.startDate.label',
                    'tooltip' => 'elemen.calendar.school_calendar.startDate.description',
                    'attr'     => ['class' => 'start'],
                    'years'    => [$minYear, $maxYear],
                ]
            )
            ->add(
                'endDate',
                OroDateType::class,
                [
                    'required' => true,
                    'label'    => 'elemen.calendar.school_calendar.endDate.label',
                    'tooltip' => 'elemen.calendar.school_calendar.endDate.description',
                    'attr'     => ['class' => 'start'],
                    'years'    => [$minYear, $maxYear],
                ]
            )
            ->add(
                'numberDays',
                IntegerType::class,
                [
                    'required' => true,
                    'label'    => 'elemen.calendar.system.number_days.label',
                    'tooltip'  => 'elemen.calendar.school_calendar.form.number_days.description',
                    'empty_data' => 5,
                    'mapped'   => false,
                    'constraints' => [
                        new Assert\NotBlank(),
                        new Assert\Range([
                            'min' => 1,
                            'max' => 7,
                            'minMessage' => 'Number of days is too small.',
                            'maxMessage' => 'Number of days is too big.'
                        ]),
                    ],
                ]
            )
            ->add(
                'numberPeriods',
                IntegerType::class,
                [
                    'required' => true,
                    'label'    => 'elemen.calendar.system.number_periods.label',
                    'tooltip'  => 'elemen.calendar.system.number_periods.description',
                    'empty_data' => 4,
                    'mapped'   => false,
                    'constraints' => [
                        new Assert\NotBlank(),
                        new Assert\Range([
                            'min' => 1,
                            'max' => 24,
                            'minMessage' => 'Number of days is too small.',
                            'maxMessage' => 'Number of days is too big.'
                        ]),
                    ],

                ]
            )
        ;
        $builder->addEventListener(FormEvents::POST_SET_DATA, [$this, 'postSetData']);

    }

    /**
     * POST_SET_DATA event handler
     *
     * @param FormEvent $event
     */
    public function postSetData(FormEvent $event)
    {
        /** @var SystemCalendar $data */
        $data = $event->getData();
        if (!$data || !$data->getId()) {
            return;
        }

        $form = $event->getForm();
        /** @var PeriodRepository $repoPeriod */
        $repoPeriod = $this->doctrineHelper->getEntityRepositoryForClass('ElemenCalendarBundle:Period');
        $minPeriods = $repoPeriod->countPeriods($data->getId());
        /** @var CycleDayRepository $repoDay */
        $repoDay =    $this->doctrineHelper->getEntityRepositoryForClass('ElemenCalendarBundle:CycleDay');
        $minDays = $repoDay->countDays($data->getId());

        FormUtils::replaceField(
            $form,
            'numberPeriods',
            [
                'constraints' => [
                    new Assert\NotBlank(),
                    new Assert\Range([
                        'min' => $minPeriods,
                        'max' => 24,
                        'minMessage' => 'Minimum number of days: '.$minPeriods,
                        'maxMessage' => 'Number of days is too big.'
                    ]),
                ],
            ]
        );
        FormUtils::replaceField(
            $form,
            'numberDays',
            [
                'constraints' => [
                    new Assert\NotBlank(),
                    new Assert\Range([
                        'min' => $minDays,
                        'max' => 24,
                        'minMessage' => 'Minimum number of days: '.$minDays,
                        'maxMessage' => 'Number of days is too big.'
                    ]),
                ],
            ]
        );
    }
}
