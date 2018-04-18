<?php

namespace Elemen\Bundle\CalendarBundle\Twig;

use Elemen\Bundle\CalendarBundle\Entity\CycleDay;
use Elemen\Bundle\CalendarBundle\Entity\Manager\SystemCalendarManager;
use Elemen\Bundle\CalendarBundle\Entity\Period;
use Elemen\Bundle\CalendarBundle\Entity\Repository\CycleDayRepository;
use Elemen\Bundle\CalendarBundle\Entity\Repository\PeriodRepository;
use Oro\Bundle\EntityBundle\ORM\DoctrineHelper;
use Symfony\Component\Form\FormView;


class SystemCalendarExtension extends \Twig_Extension
{
    const EXTENSION_NAME = 'elemen_system_calendar_context';

    /** @var DoctrineHelper */
    protected $doctrineHelper;

    /** @var SystemCalendarManager  */
    protected $manager;

    /**
     * @param SystemCalendarManager $manager
     * @param DoctrineHelper $doctrineHelper
     */
    public function __construct(SystemCalendarManager $manager, DoctrineHelper $doctrineHelper)
    {
        $this->manager = $manager;
        $this->doctrineHelper = $doctrineHelper;
    }

    /**
     * Returns a list of functions to add to the existing list.
     *
     * @return array An array of functions
     */
    public function getFunctions()
    {
        return [
            new \Twig_SimpleFunction('get_system_calendar_context', [$this, 'getSystemCalendar']),
            new \Twig_SimpleFunction('get_system_calendar_context_id', [$this, 'getSystemCalendarId']),
            new \Twig_SimpleFunction('get_system_calendars', [$this, 'getSystemCalendars']),
            new \Twig_SimpleFunction('schedule_form_to_ids_array', [$this, 'scheduleFormToIdsArray']),
            new \Twig_SimpleFunction('get_periods', [$this, 'getPeriods']),
            new \Twig_SimpleFunction('get_days', [$this, 'getDays']),
        ];
    }

    /**
     * @return array|Period[]
     */
    public function getPeriods()
    {
        /** @var PeriodRepository $repo */
        $repo = $this->doctrineHelper->getEntityRepository(Period::class);
        return $repo->getPeriodsByCalendarId($this->getSystemCalendarId());
    }

    /**
     * @return array|CycleDay[]
     */
    public function getDays()
    {
        /** @var CycleDayRepository $repo */
        $repo = $this->doctrineHelper->getEntityRepository(CycleDay::class);
        return $repo->getCycleDaysByCalendarId($this->getSystemCalendarId());
    }

    /**
     * @param FormView $form
     * @return array|FormView[]
     */
    public function scheduleFormToIdsArray(FormView $form)
    {
        $arr = [];
        foreach ($form as $child) {
            $arr[$child->vars['value']] = $child;
        }
        return $arr;
    }

    /**
     * @return \Oro\Bundle\CalendarBundle\Entity\SystemCalendar
     */
    public function getSystemCalendar()
    {
        return $this->manager->getCalendarContext();
    }

    /**
     * @return int
     */
    public function getSystemCalendarId()
    {
       return $this->manager->getCalendarContext()->getId();
    }

    /**
     * @return \Oro\Bundle\CalendarBundle\Entity\SystemCalendar[]
     */
    public function getSystemCalendars()
    {
        return $this->manager->findAllowedSystemCalendars();
    }


    /**
     * Returns the name of the extension.
     *
     * @return string The extension name
     */
    public function getName()
    {
        return self::EXTENSION_NAME;
    }
}
