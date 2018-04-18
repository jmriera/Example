<?php
/**
 * Created by PhpStorm.
 * User: juan
 * Date: 26/11/17
 * Time: 12:40
 */

namespace Elemen\Bundle\CalendarBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

use Elemen\Bundle\CalendarBundle\Model\ExtendPeriod;
use Oro\Bundle\CalendarBundle\Entity\SystemCalendar;
use Oro\Bundle\EntityConfigBundle\Metadata\Annotation\Config;
use Oro\Bundle\EntityConfigBundle\Metadata\Annotation\ConfigField;

use Oro\Bundle\EntityBundle\EntityProperty\DatesAwareInterface;
use Oro\Bundle\EntityBundle\EntityProperty\DatesAwareTrait;
use Oro\Bundle\OrganizationBundle\Entity\OrganizationAwareInterface;
use Oro\Bundle\OrganizationBundle\Entity\Ownership\OrganizationAwareTrait;

/**
 * @ORM\Entity
 * @ORM\Table(name="elemen_calendar_period")
 * @ORM\Entity(repositoryClass="Elemen\Bundle\CalendarBundle\Entity\Repository\PeriodRepository")
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
class Period extends ExtendPeriod implements DatesAwareInterface, OrganizationAwareInterface
{
    use DatesAwareTrait, OrganizationAwareTrait;

    public function __construct(string $shortName = null, string $name = null, int $priority = null, SystemCalendar $entity = null)
    {
        parent::__construct();

        $this->name = $name;
        $this->shortName = $shortName;
        $this->priority = $priority;
        $this->systemCalendar = $entity;
    }

    /**
     * @ORM\Id
     * @ORM\Column(type="integer", name="id")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=255)
     */
    protected $name;

    /**
     * @var string
     *
     * @ORM\Column(type="string",name="short_name", length=3)
     */
    protected $shortName;

    /**
     * @var int
     *
     * @ORM\Column(type="integer")
     */
    protected $priority;

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     * @return $this
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
     * @return $this
     */
    public function setName(string $name)
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return string
     */
    public function getShortName()
    {
        return $this->shortName;
    }

    /**
     * @param string $shortName
     * @return $this
     */
    public function setShortName(string $shortName)
    {
        $this->shortName = $shortName;
        return $this;
    }

    /**
     * @return int
     */
    public function getPriority()
    {
        return $this->priority;
    }

    /**
     * @param int $priority
     * @return $this
     */
    public function setPriority(int $priority)
    {
        $this->priority = $priority;
        return $this;
    }


}