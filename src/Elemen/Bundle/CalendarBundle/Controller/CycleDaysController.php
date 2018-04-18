<?php

namespace Elemen\Bundle\CalendarBundle\Controller;

use Elemen\Bundle\CalendarBundle\Entity\Repository\CycleDayRepository;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

use Oro\Bundle\SecurityBundle\Annotation\Acl;

class CycleDaysController extends Controller
{

    /**
     * Update Period form
     * @Route("/update/{entityClass}/{entityId}", name="elemen_cycle_days_update")
     * @Template
     * @Acl(
     *      id="elemen_cycle_days_update",
     *      type="entity",
     *      permission="EDIT",
     *      class="ElemenCalendarBundle:CycleDay"
     * )
     * @param string  $entityClass The entity class which activities should be rendered
     * @param integer $entityId    The entity object id which activities should be rendered
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function updateAction($entityClass, $entityId)
    {
        return $this->update($this->getCycleDays($entityId));
    }

    protected function update($items)
    {
        return $this->get('elemen_core.collection_update_handler')->update(
            $items,
            $this->get('elemen_calendar.form.cycle_day_collection'),
            $this->get('translator')->trans('elemen.core.controller.entity.saved.message'),null,
            'cycleday_collection_form_handler'
        );
    }

    protected function getCycleDays($systemCalendarId)
    {
        /** @var CycleDayRepository $repository */
        $repository = $this->getDoctrine()->getRepository('ElemenCalendarBundle:CycleDay');

        return $repository->getCycleDaysByCalendarId($systemCalendarId);

    }

}
