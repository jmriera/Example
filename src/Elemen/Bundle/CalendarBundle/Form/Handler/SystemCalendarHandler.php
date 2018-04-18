<?php

namespace Elemen\Bundle\CalendarBundle\Form\Handler;

use Doctrine\Common\Persistence\ObjectManager;

use Elemen\Bundle\CalendarBundle\Entity\CycleDay;
use Elemen\Bundle\CalendarBundle\Entity\Period;
use Elemen\Bundle\CalendarBundle\Entity\Repository\CycleDayRepository;
use Elemen\Bundle\CalendarBundle\Entity\Repository\PeriodRepository;
use Elemen\Bundle\CalendarBundle\Entity\Schedule;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;

use Oro\Bundle\CalendarBundle\Entity\SystemCalendar;

class SystemCalendarHandler extends \Oro\Bundle\CalendarBundle\Form\Handler\SystemCalendarHandler
{

    /** @var  CycleDayRepository $cycleDayRepository */
    protected $cycleDayRepository;

    /** @var PeriodRepository $periodRepository */
    protected $periodRepository;

    public function __construct(FormInterface $form, Request $request, ObjectManager $manager)
    {
        parent::__construct($form, $request, $manager);

        $this->cycleDayRepository = $this->manager->getRepository(CycleDay::class);
        $this->periodRepository = $this->manager->getRepository(Period::class);

    }


    /**
     * Process form
     *
     * @param SystemCalendar $entity
     *
     * @return bool True on successful processing, false otherwise
     * @throws \Doctrine\ORM\NoResultException
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function process(SystemCalendar $entity)
    {
        $this->form->setData($entity);
        $this->form->get('numberDays')->setData($this->cycleDayRepository->countDays($entity->getId()));
        $this->form->get('numberPeriods')->setData($this->periodRepository->countPeriods($entity->getId()));

        if (in_array($this->request->getMethod(), array('POST', 'PUT'))) {
            $this->form->submit($this->request);

            if ($this->form->isValid()) {
                $this->onSuccess($entity);

                return true;
            }
        }

        return false;
    }

    /**
     * "Success" form handler
     *
     * @param SystemCalendar $entity
     */
    protected function onSuccess(SystemCalendar $entity)
    {
        $this->manager->persist($entity);
        $this->manager->flush();
        $numberDays = $this->form->get('numberDays')->getData();
        $numberPeriods = $this->form->get('numberPeriods')->getData();

        $this->cycleDayRepository->updateDefaultCycleDays($numberDays,$entity);
        $this->periodRepository->updateDefaultPeriods($numberPeriods,$entity);
        // Update Schedule grid
        $this->manager->getRepository(Schedule::class)->createScheduleRecords(
            $this->cycleDayRepository->getCycleDaysByCalendarId($entity->getId()),
            $this->periodRepository->getPeriodsByCalendarId($entity->getId()),
            $entity
        );
    }
}
