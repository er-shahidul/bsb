<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Office;
use AppBundle\Form\Type\OfficeType;
use AppBundle\Datatables\OfficeDatatable;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

/**
 * @Route("/office")
 */
class OfficeController extends BaseController
{
    public function getHQ(){
        return $this->officeRepo()->findByOfficeType($this->officeTypeRepo()->findByName("HQ")) ? "true": "false" ;
    }

    /**
     * @Route("", name="office_list")
     * @param Request $request
     * @return \Sg\DatatablesBundle\Datatable\AbstractDatatable|\Symfony\Component\HttpFoundation\Response
     */
    public function indexAction(Request $request)
    {
        $datatable = $this->prepareDatatable(OfficeDatatable::class, $request->isXmlHttpRequest());

        if ($request->isXmlHttpRequest()) {
            return $datatable;
        }

        return $this->render('AppBundle:Office:index.html.twig', array(
            'datatable' => $datatable,
            'pageTitle' => 'Office',
            'createLink' => $this->generateUrl('office_create'),
        ));
    }

    /**
     * @Route("/new", name="office_create")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function newAction(Request $request)
    {
        $office = new Office();
        $form = $this->createForm(OfficeType::class, $office, ['hq' => $this->getHQ(), "switch" => false]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();

            $em->persist($office);
            $em->flush();

            return $this->redirectToRoute('office_list');
        }

        return $this->render('AppBundle:Office:form.html.twig', array(
            'pageTitle' => 'Office Create',
            'form' => $form->createView(),
        ));
    }

    /**
     * @Route("/{id}/edit", name="office_edit")
     * @Method({"GET", "POST"})
     * @param Request $request
     * @param Office $office
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function editAction(Request $request, Office $office)
    {
        $editForm = $this->createForm(OfficeType::class, $office, ['hq' => $this->getHQ(), "switch" => true]);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->flush();

            return $this->redirectToRoute('office_list');
        }

        return $this->render('AppBundle:Office:form.html.twig', array(
            'pageTitle' => 'Edit Office',
            'form' => $editForm->createView()
        ));
    }

    /**
     * @Route("/office_auto_complete", name="office_auto_complete", options={"expose"=true})
     * @param Request $request
     * @return JsonResponse
     */
    public function officeAutoCompleteAction(Request $request)
    {

        $office = $this->getDoctrine()->getRepository('AppBundle:Office')
            ->officeAutoComplete();

        /** @var $personalNoQuery */
        return new JsonResponse($office);
    }
}
