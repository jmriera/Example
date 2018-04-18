<?php
/**
 * Created by PhpStorm.
 * User: juan
 * Date: 05/04/17
 * Time: 19:19
 */

namespace Elemen\Bundle\CalendarBundle\Handler;

use Doctrine\Common\Persistence\ObjectManager;
use Oro\Bundle\SoapBundle\Handler\DeleteHandler;

class BellScheduleDeleteHandler extends DeleteHandler
{
    /**
     * {@inheritdoc}
     */
    protected function checkPermissions($entity, ObjectManager $em)
    {
        parent::checkPermissions($entity, $em);
        /** @todo Implement */

    }
}