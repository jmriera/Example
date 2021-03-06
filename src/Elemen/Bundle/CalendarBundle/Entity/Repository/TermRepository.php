<?php

namespace Elemen\Bundle\CalendarBundle\Entity\Repository;

use Doctrine\ORM\EntityRepository;

/**
 * TermRepository
 *
 * This class was generated by the PhpStorm "Php Annotations" Plugin. Add your own custom
 * repository methods below.
 */
class TermRepository extends EntityRepository
{
    /**
     * @param $calendarId
     * @return array
     */
    public function getTermsByCalendarId($calendarId)
    {
        return $this->getTermsByCalendarIdQueryBuilder($calendarId)
            ->getQuery()
            ->getResult();
    }

    public function getTermsByCalendarIdQueryBuilder($calendarId)
    {
        return $this->getQueryBuilder()
            ->leftJoin('term.systemCalendar','sc')
            ->where('sc.id = :id')
            ->setParameter('id', $calendarId)
            ->orderBy('term.id', 'asc');
    }

    /**
     * @return \Doctrine\ORM\QueryBuilder
     */
    public function getQueryBuilder()
    {
        return $this->createQueryBuilder('term');
    }
}
