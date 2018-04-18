<?php
/**
 * Created by PhpStorm.
 * User: juan
 * Date: 16/11/17
 * Time: 03:33
 */

namespace Elemen\Bundle\CalendarBundle\Entity\Manager;


use Doctrine\ORM\EntityManager;
use Elemen\Bundle\CalendarBundle\Entity\Term;

class TermManager
{
    /** @var EntityManager */
    protected $em;

    /**
     * @param EntityManager $em
     */
    public function __construct(EntityManager $em ) {
        $this->em = $em;
    }

    public function createEntity()
    {
        return new Term();
    }

    /**
     * Persist Term
     * @param Term $term
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function save($term)
    {
        $this->em->persist($term);
        $this->em->flush();
    }
}