<?php

namespace MedicalBundle\Controller;

use MedicalBundle\Datatables\MedicineDatatable;
use MedicalBundle\Entity\Medicine;
use MedicalBundle\Form\MedicineType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sg\DatatablesBundle\Datatable\DatatableInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class MedicineController
 * @Security("has_role('ROLE_ADMIN')")
 * @package MedicalBundle\Controller
 */
class MedicineController extends BaseMedicalController
{
    /**
     * @Route("/medicines", name="medical_medicine_list")
     */
    public function indexAction(Request $request)
    {
        /** @var DatatableInterface|Response $datatable */
        $datatable = $this->prepareDatatable(MedicineDatatable::class, $request->isXmlHttpRequest());

        if ($request->isXmlHttpRequest()) {
            return $datatable;
        }

        return $this->render('MedicalBundle:Medicine:index.html.twig', array(
            'datatable' => $datatable,
        ));
    }

    /**
     * Creates a new Medicine entity.
     *
     * @Route("/new", name="medical_medicine_new")
     * @Method({"GET", "POST"})
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function newAction(Request $request)
    {
        $medicine = new Medicine();
        $form = $this->createForm(MedicineType::class, $medicine);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($medicine);
            $em->flush();

            $this->get('session')->getFlashBag()->add(
                'success',
                'New Medicine Created Successfully'
            );

            return $this->redirectToRoute('medical_medicine_list');
        }

        return $this->render('@Medical/Medicine/form.html.twig', array(
            'pageTitle' => 'Medicine',
            'medicine' => $medicine,
            'form' => $form->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing fundHead entity.
     *
     * @Route("/{id}/edit", name="medical_medicine_edit")
     * @Method({"GET", "POST"})
     * @param Request  $request
     * @param Medicine $medicine
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function editAction(Request $request, Medicine $medicine)
    {
        $editForm = $this->createForm(MedicineType::class, $medicine);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            $this->get('session')->getFlashBag()->add(
                'success',
                'Medicine Updated Successfully'
            );

            return $this->redirectToRoute('medical_medicine_list');
        }

        return $this->render('@Medical/Medicine/form.html.twig', array(
            'medicine'  => $medicine,
            'form' => $editForm->createView()
        ));
    }
}
