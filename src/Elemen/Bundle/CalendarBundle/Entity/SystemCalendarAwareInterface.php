<?php
/**
 * Created by PhpStorm.
 * User: juan
 * Date: 24/10/17
 * Time: 17:43
 */

namespace Elemen\Bundle\CalendarBundle\Entity;


use Oro\Bundle\CalendarBundle\Entity\SystemCalendar;

interface SystemCalendarAwareInterface
{
    /**
     * @param SystemCalendar $systemCalendar
     */
    public function setSystemCalendar(SystemCalendar $systemCalendar);

    /**
     * @return SystemCalendar
     */
    public function getSystemCalendar();
}

