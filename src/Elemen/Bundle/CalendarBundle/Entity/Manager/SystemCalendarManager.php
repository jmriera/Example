<?php

namespace Elemen\Bundle\CalendarBundle\Entity\Manager;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\NoResultException;
use Doctrine\ORM\QueryBuilder;
use DotMailer\Api\Rest\NotFoundException;
use Elemen\Bundle\CalendarBundle\Model\CalendarType;
use Oro\Bundle\CalendarBundle\Entity\Repository\SystemCalendarRepository;
use Oro\Bundle\CalendarBundle\Entity\SystemCalendar;
use Elemen\Bundle\SecurityBundle\Authentication\TokenAccessorInterface;
use Oro\Bundle\SecurityBundle\ORM\Walker\AclHelper;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;


class SystemCalendarManager
{
    /** @var EntityManager */
    protected $em;

    /**
     * Elemen Token Accessor
     * @var TokenAccessorInterface
     */
    protected $tokenAccessor;

    /**
     * @var AclHelper
     */
    protected $aclHelper;

    /**
     * @param EntityManager $em
     * @param TokenAccessorInterface $tokenAccessor
     * @param AclHelper $aclHelper
     */
    public function __construct(EntityManager $em ,TokenAccessorInterface $tokenAccessor,AclHelper $aclHelper) {
        $this->em = $em;
        $this->tokenAccessor = $tokenAccessor;
        $this->aclHelper = $aclHelper;
    }

    public function setCalendarContext(SystemCalendar $calendar = null)
    {
        if ($calendar == null) {
            $this->tokenAccessor->setSystemCalendar($this->getCurrentSystemCalendar());
            return;
        }
        $this->tokenAccessor->setSystemCalendar($calendar);
    }

    /**
     * @return SystemCalendar
     */
    public function getCalendarContext()
    {
        $e = $this->tokenAccessor->getSystemCalendar();
        return $e;

    }

    /**
     * @param string $permission
     *
     * @return SystemCalendar[]
     */
    public function findAllowedSystemCalendars($permission = 'VIEW')
    {
        $qb = $this->getQueryBuilder();
        return $this->aclHelper->apply($qb, $permission)->execute();
    }

    /**
     * Finds the Current Calendar
     * @return SystemCalendar
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function getCurrentSystemCalendar()
    {
          try {
            $calendar = $this->getQueryBuilder()
                ->select('sc')
                ->where('sc.type = :type')
                ->setParameter('type', CalendarType::TYPE_CURRENT)
                ->getQuery()
                ->getSingleResult();
        } catch (NoResultException $exception) {
            throw new NotFoundHttpException('Error: It looks like the current system calendar was not found');
        }

        return $calendar;
    }

    /**
     * Returns Planning Calendars
     *
     * @return array
     */
    public function getPlanningSystemCalendar()
    {
        return $this->getQueryBuilder()
            ->select('sc')
            ->where('sc.type = :type')
            ->setParameter('type', CalendarType::TYPE_PLANNING)
            ->getQuery()
            ->getResult();
    }

    /**
     * Returns Historical Calendars
     *
     * @return array
     */
    public function getHistoricalSystemCalendars()
    {
        return $this->getQueryBuilder()
            ->select('sc')
            ->where('sc.type = :type')
            ->setParameter('type', CalendarType::TYPE_HISTORICAL)
            ->getQuery()
            ->getResult();
    }

    /**
     * @return QueryBuilder
     */
    public function getQueryBuilder()
    {
        return $this->getSystemCalendarRepo()->createQueryBuilder('sc');
    }

    /**
     * @return SystemCalendarRepository
     */
    public function getSystemCalendarRepo()
    {
        return $this->em->getRepository('OroCalendarBundle:SystemCalendar');
    }

}
