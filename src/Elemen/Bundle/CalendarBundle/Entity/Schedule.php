<?php
/**
 * Created by PhpStorm.
 * User: juan
 * Date: 24/03/18
 * Time: 21:13
 */

namespace Elemen\Bundle\CalendarBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

use Elemen\Bundle\CalendarBundle\Entity\CycleDay;
use Elemen\Bundle\CalendarBundle\Entity\Period;
use Elemen\Bundle\CalendarBundle\Entity\SystemCalendarAwareInterface;
use Elemen\Bundle\CalendarBundle\Entity\SystemCalendarAwareTrait;
use Elemen\Bundle\CourseBundle\Entity\Section;
use Oro\Bundle\EntityConfigBundle\Metadata\Annotation\ConfigField;
use Oro\Bundle\CalendarBundle\Entity\SystemCalendar;

/**
 * @ORM\Entity(repositoryClass="Elemen\Bundle\CalendarBundle\Entity\Repository\ScheduleRepository")
 * @ORM\Table(name="elemen_schedule")
 */
class Schedule implements SystemCalendarAwareInterface
{
    use SystemCalendarAwareTrait;

    /**
     * @var Period
     *
     * @ORM\Id
     * @ORM\OneToOne(targetEntity="Elemen\Bundle\CalendarBundle\Entity\Period",fetch="EAGER")
     * @ORM\JoinColumn(name="period_id", referencedColumnName="id",onDelete="CASCADE")
     */
    protected $period;

    /**
     * @var CycleDay
     *
     * @ORM\Id
     * @ORM\OneToOne(targetEntity="Elemen\Bundle\CalendarBundle\Entity\CycleDay",fetch="EAGER")
     * @ORM\JoinColumn(name="day_id", referencedColumnName="id",onDelete="CASCADE")
     */
    protected $day;

    /**
     * @var ArrayCollection
     *
     * @ORM\ManyToMany(targetEntity="Elemen\Bundle\CourseBundle\Entity\Section", mappedBy="schedules")
     * @ORM\JoinTable(name="elemen_section_to_schedule")
     * @ConfigField(
     *      defaultValues={
     *          "importexport"={
     *              "order"=240,
     *              "short"=true
     *          },
     *          "merge"={
     *              "display"=true
     *          }
     *      }
     * )
     */
    protected $sections;

    /**
     * Schedule constructor.
     * @param Period $period
     * @param CycleDay $day
     * @param SystemCalendar $systemCalendar
     */
    public function __construct(Period $period, CycleDay $day, SystemCalendar $systemCalendar)
    {
        $this->sections = new ArrayCollection();

        $this->period = $period;
        $this->day = $day;
        $this->systemCalendar = $systemCalendar;
    }

    /**
     * @return Period
     */
    public function getPeriod()
    {
        return $this->period;
    }

    /**
     * @return CycleDay
     */
    public function getDay()
    {
        return $this->day;
    }

    /**
     * @return ArrayCollection|Section[]
     */
    public function getSections()
    {
        return $this->sections;
    }

    /**
     * @param ArrayCollection $sections
     * @return Schedule
     */
    public function setSections(ArrayCollection $sections)
    {
        $this->sections = $sections;
        return $this;
    }

    /**
     * @param Section $section
     * @return $this
     */
    public function addSection(Section $section)
    {
        if (!$this->getSections()->contains($section)) {
            $this->getSections()->add($section);
            $section->addSchedule($this);
        }
        return $this;
    }

    /**
     * @param Section $section
     * @return $this
     */
    public function removeSection(Section $section)
    {
        if ($this->getSections()->contains($section)) {
            $this->getSections()->removeElement($section);
            $section->removeSchedule($this);
        }
        return $this;
    }

    public function __toString()
    {
        return (string)$this->getDay()->getShortName() . '(' . $this->getPeriod()->getShortName() . ')';
    }

}