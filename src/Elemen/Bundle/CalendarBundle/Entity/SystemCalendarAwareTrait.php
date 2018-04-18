<?php
/**
 * Created by PhpStorm.
 * User: juan
 * Date: 24/10/17
 * Time: 17:50
 */

namespace Elemen\Bundle\CalendarBundle\Entity;


use Oro\Bundle\CalendarBundle\Entity\SystemCalendar;

trait SystemCalendarAwareTrait
{
    /**
     * @var SystemCalendar
     *
     * @ORM\ManyToOne(targetEntity="Oro\Bundle\CalendarBundle\Entity\SystemCalendar")
     * @ORM\JoinColumn(name="system_calendar_id", referencedColumnName="id", onDelete="SET NULL")
     */
    protected $systemCalendar;

    /**
     * @return SystemCalendar|null
     */
    public function getSystemCalendar()
    {
        return $this->systemCalendar;
    }

    /**
     * @param SystemCalendar|null $systemCalendar
     * @return $this
     */
    public function setSystemCalendar(SystemCalendar $systemCalendar)
    {
        $this->systemCalendar = $systemCalendar;

        return $this;
    }
}