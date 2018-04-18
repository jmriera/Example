<?php
/**
 * Created by PhpStorm.
 * User: juan
 * Date: 18/10/17
 * Time: 22:01
 */

namespace Elemen\Bundle\CalendarBundle\Controller;

use Elemen\Bundle\CalendarBundle\Entity\Manager\TermManager;
use Elemen\Bundle\CalendarBundle\Entity\Term;
use Oro\Bundle\CalendarBundle\Entity\SystemCalendar;
use Oro\Bundle\EntityBundle\Tools\EntityRoutingHelper;
use Oro\Bundle\FormBundle\Utils\FormUtils;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;

use Oro\Bundle\SecurityBundle\Annotation\Acl;

class TermController extends Controller
{

    /**
     * Create Term
     * @Route("/create", name="elemen_term_create")
     * @Template("ElemenCalendarBundle:Term:update.html.twig")
     * @Acl(
     *      id="elemen_term_create",
     *      type="entity",
     *      permission="CREATE",
     *      class="ElemenCalendarBundle:Term"
     * )
     * @param Request $request
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function createAction(Request $request)
    {
        $term = $this->getManager()->createEntity();

        $entityRoutingHelper = $this->getEntityRoutingHelper();
        $entityClass = $entityRoutingHelper->getEntityClassName($request);
        $entityId = $entityRoutingHelper->getEntityId($request);

        if ($entityClass && $request->getMethod() === 'GET'
            && is_a($entityClass, 'Oro\Bundle\CalendarBundle\Entity\SystemCalendar', true)
        ) {
            /** @var SystemCalendar $calendar */
            $calendar = $entityRoutingHelper->getEntity($entityClass, $entityId);
            $term->setSystemCalendar($calendar);
        }


        return $this->update($term);

    }

    /**
     * Update term form
     * @Route("/update/{id}", name="elemen_term_update", requirements={"id"="\d+"})
     * @Template
     * @Acl(
     *      id="elemen_term_update",
     *      type="entity",
     *      permission="EDIT",
     *      class="ElemenCalendarBundle:Term"
     * )
     * @param Term $entity
     * @param Request $request
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function updateAction(Term $entity, Request $request)
    {
        return $this->update($entity,true);
    }

    protected function update(Term $entity,$update = false)
    {
        return $this->get('oro_form.update_handler')->update(
            $entity,
            $this->get('elemen_calendar.form.term'),
            $this->get('translator')->trans('elemen.core.controller.entity.saved.message'),null,null,
            function ($data, FormInterface $form, Request $request) use ($update){

                if((bool)$request->get('_wid', false) && !$form->isSubmitted())
                    FormUtils::replaceField($form, 'systemCalendar', ['read_only' => true]);

                return [
                    'update'    => $update,
                    'form'      => $form->createView()
                ];
            }
        );
    }

    /**
     * @return TermManager
     */
    protected function getManager()
    {
        return $this->get('elemen_calendar.term.manager');
    }

    /**
     * @return EntityRoutingHelper
     */
    protected function getEntityRoutingHelper()
    {
        return $this->get('oro_entity.routing_helper');
    }

}