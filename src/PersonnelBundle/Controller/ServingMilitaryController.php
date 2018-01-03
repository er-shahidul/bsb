<?php

namespace PersonnelBundle\Controller;

use PersonnelBundle\Entity\ServingMilitary;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use PersonnelBundle\Form\ServingMilitaryType;
use PersonnelBundle\Datatables\ServingMilitaryDatatable;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

/**
 * @Route("/serving-military")
 */
class ServingMilitaryController extends PersonnelBaseController
{
    /**
     * @Route("", name="serving_military_list")
     * @param Request $request
     * @return \Sg\DatatablesBundle\Datatable\AbstractDatatable|\Symfony\Component\HttpFoundation\Response
     */
    public function indexAction(Request $request)
    {
        $datatable =  $this->prepareDatatable(ServingMilitaryDatatable::class, $request->isXmlHttpRequest());

        if ($request->isXmlHttpRequest()) {
            return $datatable;
        }

        return $this->render('@Personnel/Default/base_list_page.html.twig', array(
            'datatable' => $datatable,
            'pageTitle' => 'Serving Military',
            'createLink' => $this->generateUrl('serving_military_create'),
        ));
    }

    /**
     * @Route("/new", name="serving_military_create")
     * @Security("has_role('ROLE_ESTABLISHMENT_CLERK')")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function newAction(Request $request)
    {
        $servingMilitary = new ServingMilitary();
        $form = $this->createForm(ServingMilitaryType::class, $servingMilitary);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $servingMilitary->setGender($this->genderRepo()->find($servingMilitary->getGender()));

            $em->persist($servingMilitary);
            $em->flush();

            return $this->redirectToRoute('serving_military_list');
        }

        return $this->render('@Personnel/ServingMilitary/form.html.twig', array(
            'form' => $form->createView(),
        ));
    }

    /**
     * @Route("/{id}/edit", name="serving_military_edit")
     * @Method({"GET", "POST"})
     * @Security("has_role('ROLE_ESTABLISHMENT_CLERK')")
     * @param Request $request
     * @param ServingMilitary $servingMilitary
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function editAction(Request $request, ServingMilitary $servingMilitary)
    {

        $editForm = $this->createForm(ServingMilitaryType::class, $servingMilitary);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $servingMilitary->setGender($this->genderRepo()->find($servingMilitary->getGender()));

            $em->flush();

            return $this->redirectToRoute('serving_military_list');
        }

        return $this->render('@Personnel/ServingMilitary/form.html.twig', array(
            'form' => $editForm->createView()
        ));
    }

    /**
     * @Route("/view/{id}", name="serving_military_view")
     * @param ServingMilitary $servingMilitary
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function viewAction(ServingMilitary $servingMilitary)
    {
        return $this->render('PersonnelBundle:ServingMilitary:show.html.twig', array(
            'personnel' => $servingMilitary,
        ));
    }

    /**
     * @Route("/personal_no_auto_complete", name="personal_no_auto_complete", options={"expose"=true})
     * @param Request $request
     * @return JsonResponse
     */
    public function personalNoAutoCompleteAction(Request $request)
    {
        $personalNo = $this->getDoctrine()->getRepository('PersonnelBundle:ServingMilitary')
            ->personalNo();

        /** @var $personalNoQuery */
        return new JsonResponse($personalNo);
    }
}
