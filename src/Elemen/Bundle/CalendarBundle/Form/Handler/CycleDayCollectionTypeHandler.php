<?php
/**
 * Created by PhpStorm.
 * User: juan
 * Date: 26/11/17
 * Time: 13:31
 */

namespace Elemen\Bundle\CalendarBundle\Form\Handler;


use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Persistence\ObjectManager;
use Elemen\Bundle\CalendarBundle\Entity\Period;
use Elemen\Bundle\CoreBundle\Form\Handler\FormCollectionHandler;
use Oro\Bundle\CalendarBundle\Entity\SystemCalendar;
use Oro\Bundle\EntityBundle\ORM\DoctrineHelper;
use Oro\Bundle\EntityBundle\Tools\EntityRoutingHelper;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;

class CycleDayCollectionTypeHandler extends FormCollectionHandler
{
    /** @var  EntityRoutingHelper */
    protected $entityRoutingHelper;

    public function __construct(EventDispatcherInterface $eventDispatcher,
                                DoctrineHelper $doctrineHelper,
                                EntityRoutingHelper $entityRoutingHelper = null )
    {
        parent::__construct($eventDispatcher, $doctrineHelper);

        $this->entityRoutingHelper = $entityRoutingHelper;

    }

    protected function saveDataForCollection($data, FormInterface $form, Request $request)
    {
        $targetEntityClass   = $this->entityRoutingHelper->resolveEntityClass($request->attributes->get('entityClass'));
        $targetEntityId      = $request->attributes->get('entityId');
        /** @var SystemCalendar $entity */
        $entity = $this->entityRoutingHelper->getEntity($targetEntityClass, $targetEntityId);

        /** @var Period $data */
        $data->setSystemCalendar($entity);

        $manager = $this->doctrineHelper->getEntityManager($data);
        $manager->persist($data);

    }


}