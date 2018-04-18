<?php
/**
 * Created by PhpStorm.
 * User: juan
 * Date: 12/11/17
 * Time: 19:46
 */

namespace Elemen\Bundle\CalendarBundle\Form\Handler;

use Elemen\Bundle\CalendarBundle\Entity\Manager\CalendarConfigManager;
use Elemen\Bundle\CoreBundle\Form\Handler\FormHandler as ElemenFormHandler;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;

class CalendarConfigFormHandler extends ElemenFormHandler
{
    /**
     * @param mixed $data
     * @param FormInterface $form
     * @param Request $request
     *
     * @return bool
     */

    public function process($data, FormInterface $form, Request $request)
    {
        if (in_array($request->getMethod(), ['POST', 'PUT'],true)) {
            $form->submit($request);
            if ($form->isValid()) {
                /** @var CalendarConfigManager $configManager */
                $configManager = $this->manager;
                $configManager->updateCalendarConfig($form->getData());
                 return true;
            }
        }
        return false;
    }
}