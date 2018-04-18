<?php
/**
 * Created by PhpStorm.
 * User: juan
 * Date: 29/03/18
 * Time: 11:45
 */

namespace Elemen\Bundle\CalendarBundle\Form\DataTransformer;


use Doctrine\Common\Collections\ArrayCollection;
use Elemen\Bundle\CalendarBundle\Entity\Schedule;
use Oro\Bundle\EntityBundle\ORM\DoctrineHelper;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;

class ScheduleTransformer  implements DataTransformerInterface
{
    /** @var DoctrineHelper */
    private $doctrineHelper;

    /**
     * PeriodDayChoiceType constructor.
     * @param DoctrineHelper $doctrineHelper
     */
    public function __construct(DoctrineHelper $doctrineHelper)
    {
        $this->doctrineHelper = $doctrineHelper;
    }


    public function transform($collection)
    {
        $result = [];
        if (!($collection instanceof \ArrayAccess)) {
            return $result;
        }
        /** @var Schedule $schedule */
        foreach ($collection as $schedule) {
            $result[] = $schedule->getPeriod()->getId() . ';' . $schedule->getDay()->getId();
        }
        return $result;
    }

    public function reverseTransform($value)
    {
        return $value;
    }

}