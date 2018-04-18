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

class ScheduleViewTransformer  implements DataTransformerInterface
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


    public function reverseTransform($value)
    {
        return $value;
    }

    public function transform($values)
    {
        if (!is_array($values)) {
            throw new TransformationFailedException('Expected an array.');
        }

        if (!count($values)) {
            return null;
        }

        $collection = [];
        foreach ($values as $value) {
            $ids = explode(';', $value);
            $schedule = $this->doctrineHelper->getEntityRepository(Schedule::class)
                ->find([
                    'period' => $ids[0],
                    'day' => $ids[1],
            ]);
            $collection[] = $schedule;
        }
        return $collection;
    }
}