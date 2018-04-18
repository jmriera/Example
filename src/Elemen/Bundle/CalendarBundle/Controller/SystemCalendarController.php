<?php
/**
 * Created by PhpStorm.
 * User: juan
 * Date: 18/10/17
 * Time: 22:01
 */

namespace Elemen\Bundle\CalendarBundle\Controller;

use Oro\Bundle\CalendarBundle\Controller\SystemCalendarController as baseSystemCalendarController;

use Oro\Bundle\CalendarBundle\Entity\SystemCalendar;
use Oro\Bundle\SecurityBundle\Annotation\Acl;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\Form\FormView;
use Symfony\Component\HttpFoundation\Request;

class SystemCalendarController extends baseSystemCalendarController
{

    /**
     * @Route("/switch-calendar/{id}",name="elemen_system_calendar_switch", requirements={"id"="\d+"})
     * @Acl(
     *      id="elemen_school_calendar_switch",
     *      type="entity",
     *      class="OroCalendarBundle:SystemCalendar",
     *      permission="EDIT"
     * )
     * @Template("ElemenCalendarBundle:SystemCalendar/widget:switchCalendar.html.twig")
     * @param SystemCalendar $systemCalendar
     * @param Request $request
     * @return array
     */
    public function switchSystemCalendarContextAction(SystemCalendar $systemCalendar, Request $request)
    {
        if (!$request->get('_switch')) {
            return [];
        }

        $manager = $this->container->get('elemen_calendar.system_calendar_manager');
        $manager->setCalendarContext($systemCalendar);

        return ['switch' => true];
    }

    /**
     * Update Calendar Type of Day form
     * @Route("type-of-days/update", name="elemen_type_of_days_collection_update")
     * @Template("@ElemenCalendar/SystemCalendar/widget/updateTypeOfDays.html.twig")
     * @return array
     */
    public function updateTypeOfDaysAction()
    {
        $repository = $this->getDoctrine()->getRepository('ElemenCalendarBundle:TypeOfDay');
        $items = $repository->findAll();

        return $this->get('elemen_core.collection_update_handler')->update(
            $items,
            $this->get('elemen_calendar.form.type_of_day_collection'),
            $this->get('translator')->trans('elemen.core.controller.entity.saved.message'),null,
            'type_of_day_collection_form_handler'
        );

    }

}