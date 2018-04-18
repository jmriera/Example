<?php
/**
 * Created by PhpStorm.
 * User: juan
 * Date: 09/12/17
 * Time: 08:28
 */

namespace Elemen\Bundle\CalendarBundle\Entity;

use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

use Oro\Bundle\EntityConfigBundle\Metadata\Annotation\Config;
use Oro\Bundle\EntityConfigBundle\Metadata\Annotation\ConfigField;

use Oro\Bundle\EntityBundle\EntityProperty\DatesAwareInterface;
use Oro\Bundle\EntityBundle\EntityProperty\DatesAwareTrait;
use Oro\Bundle\OrganizationBundle\Entity\OrganizationAwareInterface;
use Oro\Bundle\OrganizationBundle\Entity\Ownership\OrganizationAwareTrait;

/**
 * @ORM\Entity
 * @ORM\Table(name="elemen_calendar_timetable")
 * @ORM\Entity(repositoryClass="Elemen\Bundle\CalendarBundle\Entity\Repository\TimetableRepository")
 * @Config(
 *      defaultValues={
 *          "ownership"={
 *              "owner_type"="ORGANIZATION",
 *              "owner_field_name"="organization",
 *              "owner_column_name"="organization_id"
 *          },
 *          "security"={
 *              "type"="ACL",
 *              "group_name"="elemen",
 *              "category"="calendar_management"
 *          }
 *      }
 * )
 */
class Timetable implements DatesAwareInterface, OrganizationAwareInterface, SystemCalendarAwareInterface
{
    use DatesAwareTrait, OrganizationAwareTrait,SystemCalendarAwareTrait;

    /**
     * @ORM\Id
     * @ORM\Column(type="integer", name="id")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var \DateTime
     *
     * @ORM\Column(type="time", name="start_time")
     */
    protected $startTime;
    /**
     * @var \DateTime
     *
     * @ORM\Column(type="time", name="end_time")
     */
    protected $endTime;

    /**
     * @ORM\ManyToOne(targetEntity="BellSchedule", inversedBy="timetables")
     * @ORM\JoinColumn(name="bell_schedule_id", referencedColumnName="id", onDelete="CASCADE")
     * @ConfigField(
     *      defaultValues={
     *          "importexport"={
     *              "excluded"=true
     *          }
     *      }
     * )
     */
    protected $bellSchedule;

    /**
     * @var Period
     *
     * @ORM\ManyToOne(targetEntity="Elemen\Bundle\CalendarBundle\Entity\Period")
     * @ORM\JoinColumn(name="period_id", referencedColumnName="id",onDelete="CASCADE")
     */
    protected $period;

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     * @return Timetable
     */
    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     * @return Timetable
     */
    public function setName(string $name)
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getStartTime()
    {
        return $this->startTime;
    }

    /**
     * @param \DateTime $startTime
     * @return Timetable
     */
    public function setStartTime(\DateTime $startTime)
    {
        $this->startTime = $startTime;
        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getEndTime()
    {
        return $this->endTime;
    }

    /**
     * @param \DateTime $endTime
     * @return Timetable
     */
    public function setEndTime(\DateTime $endTime)
    {
        $this->endTime = $endTime;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getBellSchedule()
    {
        return $this->bellSchedule;
    }

    /**
     * @param mixed $bellSchedule
     * @return Timetable
     */
    public function setBellSchedule($bellSchedule)
    {
        $this->bellSchedule = $bellSchedule;
        return $this;
    }

    /**
     * @return Period
     */
    public function getPeriod()
    {
        return $this->period;
    }

    /**
     * @param Period $period
     * @return Timetable
     */
    public function setPeriod(Period $period)
    {
        $this->period = $period;
        return $this;
    }


}