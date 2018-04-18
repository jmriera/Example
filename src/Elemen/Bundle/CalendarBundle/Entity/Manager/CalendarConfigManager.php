<?php
/**
 * Created by PhpStorm.
 * User: juan
 * Date: 27/02/18
 * Time: 21:24
 */

namespace Elemen\Bundle\CalendarBundle\Entity\Manager;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\QueryBuilder;
use Elemen\Bundle\CalendarBundle\Entity\CalendarConfig;
use Elemen\Bundle\CalendarBundle\Entity\Repository\CalendarConfigRepository;
use Elemen\Bundle\SecurityBundle\Authentication\TokenAccessorInterface;

class CalendarConfigManager
{
    private $dayOfWeek = [
            'sunday'    => 1,
            'monday'    => 2,
            'tuesday'   => 3,
            'wednesday' => 4,
            'thursday'  => 5,
            'friday'    => 6,
            'saturday'  => 7
    ];

    /** @var EntityManager */
    protected $em;
    /**
     * Elemen Token Accessor
     * @var TokenAccessorInterface
     */
    protected $tokenAccessor;

    /**
     * @param EntityManager $em
     * @param TokenAccessorInterface $tokenAccessor
     */
    public function __construct(EntityManager $em,TokenAccessorInterface $tokenAccessor ) {
        $this->em = $em;
        $this->tokenAccessor = $tokenAccessor;

    }

    public function updateCalendarConfig($data)
    {
        $qb = $this->getQueryBuilder()->update()
                    ->set('cc.inSession',':inSession')->setParameter('inSession',$data['inSession']);

        if($data['typeOfDay'])
            $qb->set('cc.typeOfDay',':typeOfDay')->setParameter('typeOfDay',$data['typeOfDay']);
        if($data['attendanceValue'])
            $qb->set('cc.attendanceValue',':attendanceValue') ->setParameter('attendanceValue',$data['attendanceValue']);
        if($data['cycleDay'])
            $qb->set('cc.cycleDay',':cycleDay')->setParameter('cycleDay',$data['cycleDay']);
        if($data['bellSchedule'])
            $qb->set('cc.bellSchedule',':bellSchedule')->setParameter('bellSchedule',$data['bellSchedule']);
        if($data['note'])
            $qb->set('cc.note',':note')->setParameter('note',$data['note']);

        $qb->andWhere('cc.date >= :startDate')
            ->andWhere('cc.date <= :endDate')
            ->andwhere('cc.systemCalendar = :calendarContext')
            ->setParameter('startDate',$data['startDate'])
            ->setParameter('endDate',$data['endDate'])
            ->setParameter('calendarContext',$this->tokenAccessor->getSystemCalendar());


        if (count($data['dayOfWeek'])) {
            foreach ($data['dayOfWeek'] as $day)
                $array[] = $this->dayOfWeek[$day];

            $qb->andWhere('DAYOFWEEK(cc.date) IN ('.implode(", ", $array).')');
        }

        $result = $qb->getQuery()->execute();

        return $result;

    }
    /**
     * Persist CalendarConfig Entity
     * @param CalendarConfig $entity
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function save($entity)
    {
        $this->em->persist($entity);
        $this->em->flush();
    }

    /**
     * @return QueryBuilder
     */
    public function getQueryBuilder()
    {
        return $this->getCalendarConfigRepo()->createQueryBuilder('cc');
    }

    /**
     * @return CalendarConfigRepository
     */
    public function getCalendarConfigRepo()
    {
        return $this->em->getRepository('ElemenCalendarBundle:CalendarConfig');
    }
}