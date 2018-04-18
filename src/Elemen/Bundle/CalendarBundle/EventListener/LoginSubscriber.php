<?php

namespace Elemen\Bundle\CalendarBundle\EventListener;

use DotMailer\Api\Rest\NotFoundException;
use Elemen\Bundle\CalendarBundle\Entity\Manager\SystemCalendarManager;
use Symfony\Component\Security\Http\Event\InteractiveLoginEvent;


class LoginSubscriber
{
    /**
     * @var SystemCalendarManager
     */
    protected $systemCalendarManager;

    /**
     * @param SystemCalendarManager $systemCalendarManager
     */
    public function __construct(SystemCalendarManager $systemCalendarManager)
    {
        $this->systemCalendarManager = $systemCalendarManager;
    }

    /**
     * @param InteractiveLoginEvent $event
     * @throws NotFoundException
     */
    public function onLogin(InteractiveLoginEvent $event)
    {
        $this->systemCalendarManager->setCalendarContext();

        if(!$event->getAuthenticationToken()->getCalendarContext())
            throw new NotFoundException('System Working Calendar Not Found.');

    }
}
