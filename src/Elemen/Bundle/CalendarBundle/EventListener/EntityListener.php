<?php

namespace Elemen\Bundle\CalendarBundle\EventListener;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Event\OnFlushEventArgs;
use Doctrine\ORM\UnitOfWork;
use Elemen\Bundle\CalendarBundle\Entity\CalendarConfig;
use Elemen\Bundle\CalendarBundle\Entity\SystemCalendarAwareInterface;
use Elemen\Bundle\CalendarBundle\Model\CalendarType;
use Elemen\Bundle\SecurityBundle\Authentication\Token\SystemCalendarContextTokenInterface;
use Oro\Bundle\CalendarBundle\EventListener\EntityListener as baseEntityListener;
use Oro\Bundle\CalendarBundle\Entity\SystemCalendar;
use Oro\Bundle\EntityExtendBundle\Tools\ExtendHelper;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;

class EntityListener extends baseEntityListener
{
    /**
     * @param LifecycleEventArgs $args
     */
    public function prePersist(LifecycleEventArgs $args)
    {
        parent::prePersist($args);

        $entity = $args->getEntity();
        $em  = $args->getEntityManager();
        if ($entity instanceof SystemCalendar) {
            // Set default calendar type
            $className = ExtendHelper::buildEnumValueClassName(CalendarType::SYSTEM_CALENDAR_TYPES_CODE);
            $newType = $em->getRepository($className)->find(ExtendHelper::buildEnumValueId('planning'));

            $entity->setType($newType);

            if ($entity->isPublic()) {
                // make sure that public calendar doesn't belong to any organization
                $entity->setOrganization(null);
            } elseif (!$entity->getOrganization()) {
                // make sure an organization is set for system calendar
                $organization = $this->tokenAccessor->getOrganization();
                if ($organization) {
                    $entity->setOrganization($organization);
                }
            }
        }

        if (!$this->tokenAccessor->hasUser()) {
            return;
        }

        $this->setDefaultSystemCalendar($this->tokenAccessor->getToken(), $entity,$em);
    }

    /**
     * @param OnFlushEventArgs $event
     * @throws \Exception
     */
    public function onFlush(OnFlushEventArgs $event)
    {
        parent::onFlush($event);

        $em  = $event->getEntityManager();
        $uow = $em->getUnitOfWork();

        foreach ($uow->getScheduledEntityInsertions() as $entity) {
            if ($entity instanceof SystemCalendar) {
                $this->updateCalendarConfigs($entity, $uow,$em);
            }
        }
        foreach ($uow->getScheduledEntityUpdates() as $entity) {
            if ($entity instanceof SystemCalendar) {
                $this->updateCalendarConfigs($entity, $uow,$em);
            }
        }
    }

    /**
     * @param SystemCalendar $systemCalendar
     * @param UnitOfWork $uow
     * @param EntityManager $em
     * @throws \Exception
     */
    public function updateCalendarConfigs(SystemCalendar $systemCalendar,$uow,$em)
    {
        $start = $systemCalendar->getStartDate();
        /** @var \DateTime $end */
        $end = $systemCalendar->getEndDate();
        $periods= [];
        $periodsToRemove = [];

        //Check Max and Min dates in CalendarConfig table
        $calendarConfigPeriod = $em->getRepository('ElemenCalendarBundle:CalendarConfig')
            ->createQueryBuilder('cc')
            ->select('MIN(cc.date) as startDate, MAX(cc.date) as endDate')
            ->leftJoin('cc.systemCalendar','sc')
            ->where('sc.id = :calendarId')
            ->setParameter('calendarId',$systemCalendar->getId())
            ->getQuery()->getOneOrNullResult();

        if (($calendarConfigPeriod['startDate'] != null) && ($calendarConfigPeriod['endDate'] != null) ) {
            $calendarConfigBegin = new \DateTime($calendarConfigPeriod['startDate']);
            $calendarConfigEnd = new \DateTime($calendarConfigPeriod['endDate']);

            if ($start < $calendarConfigBegin ) {
                $periods[] = new \DatePeriod($start, new \DateInterval('P1D'),$calendarConfigBegin->modify('-1 day'));
            }elseif ($start > $calendarConfigBegin) { // remove dates
                $periodsToRemove[] = new \DatePeriod($calendarConfigBegin, new \DateInterval('P1D'),$start);
            }

            if ($end > $calendarConfigEnd ) {
                $periods[] = new \DatePeriod($calendarConfigEnd, new \DateInterval('P1D'),(clone $end)->modify('+1 day'),\DatePeriod::EXCLUDE_START_DATE);
            }elseif ($end < $calendarConfigEnd) { // remove dates
                $periodsToRemove[] = new \DatePeriod((clone $end)->modify('+1 day'), new \DateInterval('P1D'),$calendarConfigEnd);
            }
        }else{
            $periods[] = new \DatePeriod($start, new \DateInterval('P1D'),(clone $end)->modify('+1 day'));
        }

        $this->createCalendarConfig($periods,$em, $uow,$systemCalendar);
        $this->removeCalendarConfig($periodsToRemove, $em, $uow, $systemCalendar);
    }

    /**
     * @param $periods []
     * @param EntityManager $em
     * @param UnitOfWork $uow
     * @param $systemCalendar
     */
    private function createCalendarConfig($periods, $em, $uow, $systemCalendar)
    {
        foreach ($periods as $period) {
            foreach ($period as $date) {
                $calendarConfig = new CalendarConfig();
                $calendarConfig->setDate($date);
                $calendarConfig->setInSession(false);
                $calendarConfig->setSystemCalendar($systemCalendar);

                $em->persist($calendarConfig);
                $uow->computeChangeSet($this->getClassMetadata($calendarConfig, $em), $calendarConfig);
            }
        }

    }
    /**
     * @param $periods []
     * @param EntityManager $em
     * @param UnitOfWork $uow
     * @param SystemCalendar $systemCalendar
     */
    private function removeCalendarConfig($periods, $em, $uow, $systemCalendar)
    {
        foreach ($periods as $period) {
            foreach ($period as $date) {
                $calendarConfig = $em->getRepository('ElemenCalendarBundle:CalendarConfig')
                    ->findOneBy([
                        'systemCalendar' => $systemCalendar->getId(),
                        'date' => $date
                    ])
                ;
                $em->remove($calendarConfig);
                $uow->computeChangeSet($this->getClassMetadata($calendarConfig, $em), $calendarConfig);
            }
        }

    }
    private function setDefaultSystemCalendar(TokenInterface $token, $entity, EntityManager $em)
    {
        if ($token instanceof SystemCalendarContextTokenInterface && $entity instanceof SystemCalendarAwareInterface) {
            if (!$entity->getSystemCalendar()) {
                $entity->setSystemCalendar($em->merge($token->getCalendarContext()));
            }
        }
    }
}
