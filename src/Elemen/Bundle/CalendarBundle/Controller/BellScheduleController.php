<?php

namespace Elemen\Bundle\CalendarBundle\Controller;

use Elemen\Bundle\CalendarBundle\Entity\BellSchedule;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

use Oro\Bundle\SecurityBundle\Annotation\Acl;

class BellScheduleController extends Controller
{
    /**
     * @Route("/",name="elemen_bell_schedule_index")
     * @Acl(
     *      id="elemen_bell_schedule_view",
     *      type="entity",
     *      permission="VIEW",
     *      class="ElemenCalendarBundle:BellSchedule"
     * )
     * @Template
     */
    public function indexAction()
    {
        return [];
    }

    /**
     * @Route("/create",name="elemen_bell_schedule_create")
     * @Acl(
     *      id="elemen_bell_schedule_create",
     *      type="entity",
     *      permission="CREATE",
     *      class="ElemenCalendarBundle:BellSchedule"
     * )
     * @Template("ElemenCalendarBundle:BellSchedule:update.html.twig")
     */
    public function createAction()
    {
        return $this->update(new BellSchedule());
    }

    /**
     * @Route("/update/{id}", name="elemen_bell_schedule_update", requirements={"id"="\d+"})
     *
     * @Template
     * @Acl(
     *      id="elemen_bell_schedule_update",
     *      type="entity",
     *      permission="EDIT",
     *      class="ElemenCalendarBundle:BellSchedule"
     * )
     * @param BellSchedule $bellSchedule
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function updateAction(BellSchedule $bellSchedule)
    {
        return $this->update($bellSchedule);
    }

    public function update($entity)
    {
        return $this->get('oro_form.update_handler')->update(
            $entity,
            $this->get('elemen_calendar.form.bell_schedule'),
            $this->get('translator')->trans('elemen.core.controller.entity.saved.message'),null,null
        );
    }
}
