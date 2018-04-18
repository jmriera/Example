<?php
namespace Elemen\Bundle\CalendarBundle\Migrations\Data\ORM;

use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Elemen\Bundle\CalendarBundle\ElemenCalendarBundle;
use Elemen\Bundle\CalendarBundle\Model\CalendarType;
use Oro\Bundle\CalendarBundle\Entity\SystemCalendar;
use Oro\Bundle\EntityExtendBundle\Tools\ExtendHelper;
use Oro\Bundle\OrganizationBundle\Entity\Organization;


class LoadDefaultCalendarData extends AbstractFixture implements OrderedFixtureInterface
{

    /**
     * {@inheritdoc}
     */
    public function getOrder()
    {
        /*
         * This fixture has high priority because many other fixtures depends on it, so there is case when ordered
         * fixture needs to be executed also after this one
         */
        return -100;
    }

    /**
     * {@inheritdoc}
     */
    public function load(ObjectManager $manager)
    {
        /** @todo BUG: Al hacer una instalacion limpia este fixture asigna como default system calendar "planing" type  */
        // load default current system calendar
        $className = ExtendHelper::buildEnumValueClassName(CalendarType::SYSTEM_CALENDAR_TYPES_CODE);
        $type = $manager->getRepository($className)->find(ExtendHelper::buildEnumValueId('current'));

        $defaultSystemCalendar = new SystemCalendar();
        $defaultSystemCalendar
            ->setName(date('Y'))
            ->setType($type)
            ->setStartDate(new \DateTime(date("Y")."-01-01"))
            ->setEndDate(new \DateTime(date("Y")."-12-31"))
            ;
        $manager->persist($defaultSystemCalendar);


        $manager->flush();
    }
}
