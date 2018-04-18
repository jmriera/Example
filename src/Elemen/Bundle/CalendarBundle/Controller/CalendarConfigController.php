<?php

namespace Elemen\Bundle\CalendarBundle\Controller;

use Oro\Bundle\SecurityBundle\Annotation\Acl;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;

class CalendarConfigController extends Controller
{
    /**
     * @Route("/",name="elemen_calendar_config_index")
     * @Acl(
     *      id="elemen_calendar_config_view",
     *      type="entity",
     *      permission="VIEW",
     *      class="ElemenCalendarBundle:CalendarConfig"
     * )
     * @Template
     */
    public function indexAction()
    {
        return [];
    }

    /**
     * Update Calendar Configuration
     * @Route("/update", name="elemen_calendar_config_update")
     * @Acl(
     *      id="elemen_calendar_config_update",
     *      type="entity",
     *      permission="EDIT",
     *      class="ElemenCalendarBundle:CalendarConfig"
     * )
     * @Template("@ElemenCalendar/CalendarConfig/widget/updateCalendarConfig.html.twig")
     * @return array
     */
    public function updateCalendarConfigAction()
    {
        return $this->get('elemen_core.collection_update_handler')->update(
            null,
            $this->get('elemen_calendar.form.type.calendar_config'),
            $this->get('translator')->trans('elemen.core.controller.entity.saved.message'),null,
            'calendar_config_form_handler'
        );

    }

}
