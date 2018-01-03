<?php

namespace PersonnelBundle\Controller;

use PersonnelBundle\Entity\ServingCivilian;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use PersonnelBundle\Form\ServingCivilianType;
use PersonnelBundle\Datatables\ServingCivilianDatatable;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

/**
 * @Route("/serving-civilian")
 */
class ServingCivilianController extends PersonnelBaseController
{
    /**
     * @Route("", name="serving_civilian_list")
     * @Security("has_role('ROLE_ESTABLISHMENT_CLERK')")
     * @param Request $request
     * @return \Sg\DatatablesBundle\Datatable\AbstractDatatable|\Symfony\Component\HttpFoundation\Response
     */
    public function indexAction(Request $request)
    {
        $datatable = $this->prepareDatatable(ServingCivilianDatatable::class, $request->isXmlHttpRequest());

        if ($request->isXmlHttpRequest()) {
            return $datatable;
        }

        return $this->render('@Personnel/Default/base_list_page.html.twig', array(
            'datatable' => $datatable,
            'pageTitle' => 'Serving Civilian',
            'createLink' => $this->generateUrl('serving_civilian_create'),
        ));
    }

    /**
     * @Route("/new", name="serving_civilian_create")
     * @Security("has_role('ROLE_ESTABLISHMENT_CLERK')")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function newAction(Request $request)
    {
        $servingCivilian = new ServingCivilian();
        $form = $this->createForm(ServingCivilianType::class, $servingCivilian);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $servingCivilian->setGender($this->genderRepo()->find($servingCivilian->getGender()));

            $em->persist($servingCivilian);
            $em->flush();

            return $this->redirectToRoute('serving_civilian_list');
        }

        return $this->render('@Personnel/ServingCivilian/form.html.twig', array(
            'form' => $form->createView(),
        ));
    }

    /**
     * @Route("/{id}/edit", name="serving_civilian_edit")
     * @Method({"GET", "POST"})
     * @Security("has_role('ROLE_ESTABLISHMENT_CLERK')")
     * @param Request $request
     * @param ServingCivilian $servingCivilian
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function editAction(Request $request, ServingCivilian $servingCivilian)
    {
        $editForm = $this->createForm(ServingCivilianType::class, $servingCivilian);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $servingCivilian->setGender($this->genderRepo()->find($servingCivilian->getGender()));

            $em->flush();

            return $this->redirectToRoute('serving_civilian_list');
        }

        return $this->render('@Personnel/ServingCivilian/form.html.twig', array(
            'form' => $editForm->createView()
        ));
    }

    /**
     * @Route("/view/{id}", name="serving_civilian_view")
     * @Security("has_role('ROLE_ESTABLISHMENT_CLERK')")
     * @param ServingCivilian $servingCivilian
     * @Template("PersonnelBundle:ServingCivilian:show.html.twig")
     *
     * @return array
     */
    public function viewAction(ServingCivilian $servingCivilian)
    {
        return [
            'personnel' => $servingCivilian,
        ];
    }

    /**
     * @Route("/civilian_personal_no_auto_complete", name="civilian_personal_no_auto_complete", options={"expose"=true})
     * @param Request $request
     * @return JsonResponse
     */
    public function personalNoAutoCompleteAction(Request $request)
    {
        $personalNo = $this->getDoctrine()->getRepository('PersonnelBundle:ServingCivilian')
            ->personalNo();

        /** @var $personalNoQuery */
        return new JsonResponse($personalNo);
    }
}
