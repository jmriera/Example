<?php

namespace Elemen\Bundle\CalendarBundle\Entity\Repository;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Criteria;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;
use Elemen\Bundle\CalendarBundle\Entity\CycleDay;
use Elemen\Bundle\CalendarBundle\Entity\Period;
use Elemen\Bundle\CalendarBundle\Entity\Schedule;
use Elemen\Bundle\CourseBundle\Util\CartesianProduct;
use Oro\Bundle\CalendarBundle\Entity\SystemCalendar;

/**
 * ScheduleRepository
 *
 * This class was generated by the PhpStorm "Php Annotations" Plugin. Add your own custom
 * repository methods below.
 */
class ScheduleRepository extends EntityRepository
{
    public function getPeriodsAvailableByDay(CycleDay $day)
    {
        return $this->getQueryBuilder($day->getSystemCalendar()->getId())
            ->leftJoin('schedule.day','day')
            ->andWhere('day.id = :dayId')
            ->setParameter('dayId',$day->getId())
            ->getQuery()
            ->getArrayResult();
    }

    /**
     * @param array|CycleDay[] $days
     * @param array|Period[] $periods
     * @param SystemCalendar $systemCalendar
     */
    public function createScheduleRecords($days, $periods, SystemCalendar $systemCalendar)
    {
        $toAdd = [];
        $combinations = new CartesianProduct([$periods,$days]);

        $collection = new ArrayCollection($this->findBy(['systemCalendar' => $systemCalendar->getId()]));
        foreach ($combinations as $key => $combination) {
            $criteria = Criteria::create()->where(Criteria::expr()->andX(
                Criteria::expr()->eq('period', $combination[0]),
                Criteria::expr()->eq('day', $combination[1])
            ));
            $result = $collection->matching($criteria);

            if (count($result) == 0) {
                $toAdd[] = ['period' => $combination[0], 'day' => $combination[1]];
            } else {
                $collection->removeElement($result->first());
            }
        }

        foreach ($toAdd as $combination) {
            $schedule = new Schedule($combination['period'],$combination['day'],$systemCalendar);
            $this->getEntityManager()->persist($schedule);
        }
        $this->getEntityManager()->flush();
    }

    public function count($calendarId)
    {
        return $this->getQueryBuilder($calendarId)
            ->select('COUNT(sc.id)')
            ->getQuery()
            ->getSingleScalarResult();
    }

    /**
     * @param null $systemCalendarId
     * @return QueryBuilder
     */
    public function getQueryBuilder($systemCalendarId = null)
    {
        $qb = $this->createQueryBuilder('schedule');
        if ($systemCalendarId != null) {
            $qb->leftJoin('schedule.systemCalendar','sc')
                ->andWhere('sc.id = :scId')
                ->setParameter('scId', $systemCalendarId);
        }
        return $qb;

    }
}
