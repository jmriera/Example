<?php
/**
 * Created by PhpStorm.
 * User: juan
 * Date: 05/04/17
 * Time: 19:19
 */

namespace Elemen\Bundle\CalendarBundle\Handler;

use Doctrine\Common\Persistence\ObjectManager;
use Elemen\Bundle\CalendarBundle\Entity\CalendarConfig;
use Elemen\Bundle\CourseBundle\Entity\Section;
use Oro\Bundle\CalendarBundle\Entity\SystemCalendar;
use Oro\Bundle\CalendarBundle\Handler\SystemCalendarDeleteHandler as baseOroCalendarDeleteHandler;

use Oro\Bundle\SecurityBundle\Exception\ForbiddenException;

class SystemCalendarDeleteHandler extends baseOroCalendarDeleteHandler
{
    protected $reason;
    /**
     * {@inheritdoc}
     */
    protected function checkPermissions($entity, ObjectManager $em)
    {
        parent::checkPermissions($entity, $em);
        // ForbiddenActionException default reason.
        $this->reason = 'elemen_core.entity.action.delete.denied';

        if (!$this->isDeleteAllowed($entity, $em)) {
            throw new ForbiddenException($this->reason);
        }

    }

    /**
     * @param $entity
     * @param ObjectManager $em
     * @return boolean
     */
    public function isDeleteAllowed($entity, ObjectManager $em)
    {
        /** @var SystemCalendar $entity */
        if ($entity->getType()->getId() == 'current') {
            $this->reason = 'elemen.course.section.action.denied_delete.has_enrollments';
            return false;
        }

        $sections = $em->getRepository(Section::class)->count($entity->getId());
        if ($sections > 0) {
            $this->reason = 'elemen.course.section.action.denied_delete.has_enrollments';
            return false;
        }

        return true;

    }

    protected function deleteEntity($entity, ObjectManager $em)
    {
        $configs = $em->getRepository(CalendarConfig::class)->findBy(['systemCalendar' => $entity]);
        foreach ($configs as $config) {
            $em->remove($config);
        }
        parent::deleteEntity($entity, $em);
    }


}