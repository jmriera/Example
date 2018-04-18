<?php
/**
 * Created by PhpStorm.
 * User: juan
 * Date: 15/11/17
 * Time: 11:37
 */

namespace Elemen\Bundle\CalendarBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

use Oro\Bundle\EntityBundle\EntityProperty\DatesAwareInterface;
use Oro\Bundle\EntityBundle\EntityProperty\DatesAwareTrait;
use Oro\Bundle\EntityConfigBundle\Metadata\Annotation\Config;
use Oro\Bundle\EntityConfigBundle\Metadata\Annotation\ConfigField;
use Oro\Bundle\OrganizationBundle\Entity\OrganizationAwareInterface;
use Oro\Bundle\OrganizationBundle\Entity\Ownership\OrganizationAwareTrait;

use Elemen\Bundle\CalendarBundle\Model\ExtendTerm;

/**
 * @ORM\Entity(repositoryClass="Elemen\Bundle\CalendarBundle\Entity\Repository\TermRepository")
 * @ORM\Table(name="elemen_calendar_term")
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
class Term extends ExtendTerm implements DatesAwareInterface, OrganizationAwareInterface
{
    use DatesAwareTrait, OrganizationAwareTrait;

    const DIVISION_FULL_YEAR = 'year';
    const DIVISION_SEMESTER = 'semester';
    const DIVISION_QUARTERS = 'quarters';
    const DIVISION_TRIMESTER = 'trimester';


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
     * @ORM\Column(type="string", length=2)
     */
    protected $shortName;

    /**
     * @var \DateTime
     *
     * @ORM\Column(type="date")
     */
    protected $firstDay;
    /**
     * @var \DateTime
     *
     * @ORM\Column(type="date")
     */
    protected $lastDay;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=10)
     */
    protected $division;

    /**
     * @return string
     */
    public function __toString()
    {
        return (string)$this->name;
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
    public function setName($name)
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
    public function setShortName($shortName)
    {
        $this->shortName = strtoupper($shortName);
        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getFirstDay()
    {
        return $this->firstDay;
    }

    /**
     * @param \DateTime $firstDay
     * @return $this
     */
    public function setFirstDay(\DateTime $firstDay)
    {
        $this->firstDay = $firstDay;
        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getLastDay()
    {
        return $this->lastDay;
    }

    /**
     * @param \DateTime $lastDay
     * @return $this
     */
    public function setLastDay(\DateTime $lastDay)
    {
        $this->lastDay = $lastDay;
        return $this;
    }

    /**
     * @return string
     */
    public function getDivision()
    {
        return $this->division;
    }

    /**
     * @param string $division
     * @return $this
     */
    public function setDivision($division)
    {
        $this->division = $division;
        return $this;
    }

    public static function getYearDivisions()
    {
        return [
            self::DIVISION_FULL_YEAR => 'elemen.calendar.term.division.enum.full_year',
            self::DIVISION_SEMESTER  => 'elemen.calendar.term.division.enum.semester',
            self::DIVISION_QUARTERS => 'elemen.calendar.term.division.enum.quarter',
            self::DIVISION_TRIMESTER => 'elemen.calendar.term.division.enum.trimester'
        ];
    }




}