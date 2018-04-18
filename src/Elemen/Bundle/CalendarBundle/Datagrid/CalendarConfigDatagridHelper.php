<?php

namespace Elemen\Bundle\CalendarBundle\Datagrid;

use Doctrine\ORM\Query;
use Doctrine\ORM\EntityRepository;
use Elemen\Bundle\SecurityBundle\Authentication\TokenAccessor;

class CalendarConfigDatagridHelper
{
    /** @var TokenAccessor */
    protected $tokenAccessor;

    /**
     * CalendarConfigDatagridHelper constructor.
     * @param TokenAccessor $tokenAccessor
     */
    public function __construct(TokenAccessor $tokenAccessor)
    {
        $this->tokenAccessor = $tokenAccessor;
    }


    /**
     * Returns query builder callback for CycleDay Calendar Context Filter
     *
     * @return callable
     */
    public function getCycleDayFilterQueryBuilder()
    {
        return function (EntityRepository $er) {
            return $er->createQueryBuilder('cd')
                ->leftJoin('cd.systemCalendar','sc')
                ->where('sc.id = :id')
                ->setParameter('id', $this->tokenAccessor->getSystemCalendarId())
                ->orderBy('cd.priority', 'asc')
                ;
        };
    }

    /**
     * Returns query builder callback for CycleDay Calendar Context Filter
     *
     * @return callable
     */
    public function getBellScheduleFilterQueryBuilder()
    {
        return function (EntityRepository $er) {
            return $er->createQueryBuilder('bs')
                ->leftJoin('bs.systemCalendar','sc')
                ->where('sc.id = :id')
                ->setParameter('id', $this->tokenAccessor->getSystemCalendarId())
                ;
        };
    }
}
