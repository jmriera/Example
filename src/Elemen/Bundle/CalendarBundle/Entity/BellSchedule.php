<?php
/**
 * Created by PhpStorm.
 * User: juan
 * Date: 09/12/17
 * Time: 08:28
 */

namespace Elemen\Bundle\CalendarBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

use Elemen\Bundle\CalendarBundle\Model\ExtendBellSchedule;
use Oro\Bundle\EntityConfigBundle\Metadata\Annotation\Config;
use Oro\Bundle\EntityConfigBundle\Metadata\Annotation\ConfigField;

use Oro\Bundle\EntityBundle\EntityProperty\DatesAwareInterface;
use Oro\Bundle\EntityBundle\EntityProperty\DatesAwareTrait;
use Oro\Bundle\OrganizationBundle\Entity\OrganizationAwareInterface;
use Oro\Bundle\OrganizationBundle\Entity\Ownership\OrganizationAwareTrait;

/**
 * @ORM\Entity
 * @ORM\Table(name="elemen_calendar_bell_schedule")
 * @ORM\Entity(repositoryClass="Elemen\Bundle\CalendarBundle\Entity\Repository\BellScheduleRepository")
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
class BellSchedule extends ExtendBellSchedule implements DatesAwareInterface, OrganizationAwareInterface, SystemCalendarAwareInterface
{
    use DatesAwareTrait, OrganizationAwareTrait, SystemCalendarAwareTrait;

    /**
     * @ORM\Id
     * @ORM\Column(type="integer", name="id")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=150)
     */
    protected $name;

    /**
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="Elemen\Bundle\CalendarBundle\Entity\Timetable",
     *    mappedBy="bellSchedule", cascade={"all"}, orphanRemoval=true
     * )
     * @ConfigField(
     *      defaultValues={
     *          "importexport"={
     *              "full"=true,
     *              "order"=250
     *          },
     *          "dataaudit"={
     *              "auditable"=true
     *          },
     *          "merge"={
     *              "display"=true
     *          }
     *      }
     * )
     */
    protected $timetables;

    /**
     * BellSchedule constructor.
     */
    public function __construct()
    {
        $this->timetables = new ArrayCollection();
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     * @return BellSchedule
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
     * @return BellSchedule
     */
    public function setName(string $name)
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return ArrayCollection
     */
    public function getTimetables()
    {
        return $this->timetables;
    }

    /**
     * @param ArrayCollection $timetables
     * @return BellSchedule
     */
    public function setTimetables(ArrayCollection $timetables)
    {
        $this->timetables = $timetables;
        return $this;
    }


    /**
     * @param Timetable $timetable
     * @return $this
     */
    public function addTimetable(Timetable $timetable)
    {
        if (!$this->timetables->contains($timetable)) {
            $this->timetables->add($timetable);
            $timetable->setBellSchedule($this);
        }

        return $this;
    }

    /**
     * @param Timetable $timetable
     * @return $this
     */
    public function removeTimetable(Timetable $timetable)
    {
        if ($this->timetables->contains($timetable)) {
            $this->timetables->removeElement($timetable);
        }

        return $this;
    }

    /**
     * @param Timetable $timetable
     * @return bool
     */
    public function hasTimetable(Timetable $timetable)
    {
        return $this->getTimetables()->contains($timetable);
    }

    /**
     * Clone relations
     */
    public function __clone()
    {
        if ($this->timetables) {
            $this->timetables = clone $this->timetables;
        }
    }


}