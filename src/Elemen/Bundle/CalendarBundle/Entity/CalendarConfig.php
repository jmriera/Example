<?php
/**
 * Created by PhpStorm.
 * User: juan
 * Date: 27/02/18
 * Time: 19:09
 */

namespace Elemen\Bundle\CalendarBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

use Elemen\Bundle\CalendarBundle\Model\ExtendCalendarConfig;
use Oro\Bundle\EntityConfigBundle\Metadata\Annotation\Config;
use Oro\Bundle\OrganizationBundle\Entity\OrganizationAwareInterface;
use Oro\Bundle\OrganizationBundle\Entity\Ownership\OrganizationAwareTrait;

/**
 * @ORM\Entity
 * @ORM\Table(name="elemen_calendar_config")
 * @ORM\Entity(repositoryClass="Elemen\Bundle\CalendarBundle\Entity\Repository\CalendarConfigRepository")
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
class CalendarConfig extends ExtendCalendarConfig implements OrganizationAwareInterface, SystemCalendarAwareInterface
{
    use OrganizationAwareTrait, SystemCalendarAwareTrait;

    /**
     * @ORM\Id
     * @ORM\Column(type="integer", name="id")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date", type="date")
     */
    protected $date;

    /**
     * @var CycleDay
     *
     * @ORM\ManyToOne(targetEntity="Elemen\Bundle\CalendarBundle\Entity\CycleDay")
     * @ORM\JoinColumn(name="cycle_day_id", referencedColumnName="id", onDelete="SET NULL")
     */
    protected $cycleDay;

    /**
     * @var BellSchedule
     *
     * @ORM\ManyToOne(targetEntity="Elemen\Bundle\CalendarBundle\Entity\BellSchedule")
     * @ORM\JoinColumn(name="bell_schedule_id", referencedColumnName="id", onDelete="SET NULL")
     */
    protected $bellSchedule;

    /**
     * @var boolean
     *
     * @ORM\Column(name="in_session", type="boolean", options={"default"=false})
     */
    protected $inSession;
    /**
     * @var string
     *
     * @ORM\Column(name="attendance_value", type="decimal", precision=2, scale=1, nullable=true)
     */
    protected $attendanceValue;

    /**
     * @var TypeOfDay
     *
     * @ORM\ManyToOne(targetEntity="Elemen\Bundle\CalendarBundle\Entity\TypeOfDay")
     * @ORM\JoinColumn(name="type_of_day_id", referencedColumnName="id", onDelete="SET NULL")
     */
    protected $typeOfDay;

    /**
     * @var string
     *
     * @ORM\Column(name="note", type="string", nullable=true)
     */
    protected $note;

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     * @return CalendarConfig
     */
    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * @param \DateTime $date
     * @return CalendarConfig
     */
    public function setDate(\DateTime $date)
    {
        $this->date = $date;
        return $this;
    }

    /**
     * @return CycleDay
     */
    public function getCycleDay()
    {
        return $this->cycleDay;
    }

    /**
     * @param CycleDay $cycleDay
     * @return CalendarConfig
     */
    public function setCycleDay(CycleDay $cycleDay)
    {
        $this->cycleDay = $cycleDay;
        return $this;
    }

    /**
     * @return BellSchedule
     */
    public function getBellSchedule()
    {
        return $this->bellSchedule;
    }

    /**
     * @param BellSchedule $bellSchedule
     * @return CalendarConfig
     */
    public function setBellSchedule(BellSchedule $bellSchedule)
    {
        $this->bellSchedule = $bellSchedule;
        return $this;
    }

    /**
     * @return bool
     */
    public function isInSession()
    {
        return $this->inSession;
    }

    /**
     * @param bool $inSession
     * @return CalendarConfig
     */
    public function setInSession($inSession)
    {
        $this->inSession = $inSession;
        return $this;
    }

    /**
     * @return string
     */
    public function getAttendanceValue()
    {
        return $this->attendanceValue;
    }

    /**
     * @param string $attendanceValue
     * @return CalendarConfig
     */
    public function setAttendanceValue($attendanceValue)
    {
        $this->attendanceValue = $attendanceValue;
        return $this;
    }

    /**
     * @return TypeOfDay
     */
    public function getTypeOfDay()
    {
        return $this->typeOfDay;
    }

    /**
     * @param TypeOfDay $typeOfDay
     * @return CalendarConfig
     */
    public function setTypeOfDay(TypeOfDay $typeOfDay)
    {
        $this->typeOfDay = $typeOfDay;
        return $this;
    }

    /**
     * @return string
     */
    public function getNote()
    {
        return $this->note;
    }

    /**
     * @param string $note
     * @return CalendarConfig
     */
    public function setNote($note)
    {
        $this->note = $note;
        return $this;
    }


}