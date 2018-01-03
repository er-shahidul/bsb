<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Office;
use BoardMeetingBundle\Entity\BoardMeeting;
use BoardMeetingBundle\Form\BoardMeetingType;
use Sg\DatatablesBundle\Datatable\AbstractDatatable;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\EventDispatcher\Event;
use Symfony\Component\Workflow\Transition;

class BaseController extends Controller
{
    private $office;

    public function setContainer(ContainerInterface $container = null)
    {
        parent::setContainer($container);
        $this->office = method_exists($this->getUser(), 'getOffice') ? $this->getUser()->getOffice() : null;
    }

    /**
     * @param $datatableClass
     * @param boolean $isAjaxRequest
     * @param null $callback
     * @return AbstractDatatable|\Symfony\Component\HttpFoundation\Response
     */
    protected function prepareDatatable($datatableClass, $isAjaxRequest, $callback = null)
    {
        /** @var AbstractDatatable $datatable */
        $datatable =  $this->get('sg_datatables.factory')->create($datatableClass);
        $datatable->buildDatatable();

        if ($isAjaxRequest) {

            $responseService = $this
                ->get('sg_datatables.response')
                ->setDatatable($datatable);

            $datatableQueryBuilder = $responseService->getDatatableQueryBuilder();

            if ($callback && is_callable($callback)) {
                call_user_func($callback, $datatableQueryBuilder->getQb());
            }

            return $responseService->getResponse(false);
        }

        return $datatable;
    }

    /**
     * @return Office
     */
    public function getOffice()
    {
        return $this->office;
    }


    /**
     * @param $entityClass
     *
     * @return \Symfony\Component\Form\FormView
     */
    public function createMeetingForm($entityClass)
    {
        return $this->createForm(BoardMeetingType::class, BoardMeeting::create($entityClass))->createView();
    }

    protected function dispatch($eventName, Event $event)
    {
        $this->get('event_dispatcher')->dispatch($eventName, $event);
    }

    protected function getRepository($entity)
    {
        return $this->getDoctrine()->getRepository($entity);
    }

    protected function applyHiddenTransition($entity, $from, $to)
    {
        $workflow = $this->get('workflow.registry')->get($entity);

        $transition = new Transition(
            'hidden_transaction',
            $from,
            $to
        );
        $this->dispatch('workflow.entered',
            new \Symfony\Component\Workflow\Event\Event($entity, $workflow->getMarking($entity), $transition, $workflow->getName())
        );
    }

    protected function officeRepo()
    {
        return $this->getRepository('AppBundle:Office');
    }

    protected function officeTypeRepo()
    {
        return $this->getRepository('AppBundle:OfficeType');
    }
}
