<?php
/**
 * Created by PhpStorm.
 * User: juan
 * Date: 24/10/17
 * Time: 17:46
 */

namespace Elemen\Bundle\CalendarBundle\Entity;


interface SystemCalendarInterface
{
    /**
     * @return int
     */
    public function getId();

    /**
     * @return string
     */
    public function getName();
}