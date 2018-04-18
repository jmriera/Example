<?php

namespace Elemen\Bundle\CalendarBundle\Controller;

use Elemen\Bundle\CalendarBundle\Entity\Repository\PeriodRepository;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;

use Oro\Bundle\SecurityBundle\Annotation\Acl;

class PeriodController extends Controller
{
    /**
     * @Route("period/property/widget/{systemCalendarId}", name="elemen_period_property_view")
     * @Template("@ElemenCalendar/Period/property.html.twig")
     *
     * @param integer $systemCalendarId    The entity object id which activities should be rendered
     * @return array
     */
    public function widgetPropertyAction($systemCalendarId)
    {
        return [
            'periods'=> $this->getPeriods($systemCalendarId)
        ];
    }

    /**
     * Update Period form
     * @Route("/update/{entityClass}/{entityId}", name="elemen_period_collection_update")
     * @Template
     * @Acl(
     *      id="elemen_period_update",
     *      type="entity",
     *      permission="EDIT",
     *      class="ElemenCalendarBundle:Period"
     * )
     * @param string  $entityClass The entity class which activities should be rendered
     * @param integer $entityId    The entity object id which activities should be rendered
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function updateAction($entityClass, $entityId)
    {
        return $this->update($this->getPeriods($entityId));
    }

    protected function update($items)
    {
        return $this->get('elemen_core.collection_update_handler')->update(
            $items,
            $this->get('elemen_calendar.form.period_collection'),
            $this->get('translator')->trans('elemen.core.controller.entity.saved.message'),null,
            'period_collection_form_handler'
        );
    }

    protected function getPeriods($systemCalendarId)
    {
        /** @var PeriodRepository $repository */
        $repository = $this->getDoctrine()->getRepository('ElemenCalendarBundle:Period');

        return $repository->getPeriodsByCalendarId($systemCalendarId);

    }

}
