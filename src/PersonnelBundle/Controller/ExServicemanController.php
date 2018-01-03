<?php

namespace PersonnelBundle\Controller;

use Doctrine\ORM\QueryBuilder;
use Libs\Mpdf\MpdfFactory;
use PersonnelBundle\Entity\ExServiceman;
use PersonnelBundle\Form\ExServicemanType;
use Sg\DatatablesBundle\Datatable\DatatableInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use PersonnelBundle\Datatables\ExServicemanDatatable;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\Response;

/**
 * @Route("/ex-serviceman")
 */
class ExServicemanController extends PersonnelBaseController
{
    /**
     * @Route("", name="ex_serviceman_list")
     * @param Request $request
     * @Security("has_role('ROLE_USER') or has_role('ROLE_WELFARE_CLERK')")
     * @return \Sg\DatatablesBundle\Datatable\AbstractDatatable|\Symfony\Component\HttpFoundation\Response
     */
    public function indexAction(Request $request)
    {
        /** @var DatatableInterface|Response $datatable */
        $datatable = $this->prepareDatatable(ExServicemanDatatable::class, $request->isXmlHttpRequest(), function ($qb) {
            /** @var QueryBuilder $qb */
            if ($this->getOffice() && strtolower($this->getOffice()->getOfficeType()->getName()) == 'dasb') {
                $qb->andWhere("exserviceman.office = :office");
                $qb->setParameter('office', $this->getOffice());
            }
        });

        if ($request->isXmlHttpRequest()) {
            return $datatable;
        }

        return $this->render('@Personnel/ExServiceman/list.html.twig', array(
            'datatable' => $datatable,
            'pageTitle' => 'Ex-Serviceman',
            'createLink' => $this->generateUrl('ex_serviceman_create'),
        ));
    }

    /**
     * @Route("/ex_serviceman_form.pdf", name="ex_serviceman_form_pdf")
     * @Method({"GET"})
     */
    public function exServicemanFormGenerateAction()
    {

        $mpdf = MpdfFactory::create();
        $html = $this->renderView('@Personnel/Default/ex-serviceman-pdf.html.twig');
        $mpdf->WriteHTML($html);
        $mpdf->Output("ex serviceman form.pdf", 'I');
    }

    /**
     * @Route("/new", name="ex_serviceman_create")
     * @Security("has_role('ROLE_DASB_CLERK') or has_role('ROLE_WELFARE_CLERK')")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function newAction(Request $request)
    {
        $exServiceman = new ExServiceman();
        $form = $this->createForm('PersonnelBundle\Form\ExServicemanType', $exServiceman, ['office' => $this->getOffice()]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();

            if(!$exServiceman->isDeceased()) {
                $exServiceman->setDeceasedDate(NULL);
            }
            $exServiceman->setGender($this->genderRepo()->find($exServiceman->getGender()));

            $em->persist($exServiceman);
            $em->flush();

            return $this->redirectToRoute('ex_serviceman_list');
        }

        return $this->render('@Personnel/ExServiceman/form.html.twig', array(
            'pageTitle' => 'Ex-serviceman Create',
            'form' => $form->createView(),
        ));
    }

    /**
     * @Route("/{id}/edit", name="ex_serviceman_edit")
     * @Method({"GET", "POST"})
     * @Security("has_role('ROLE_DASB_CLERK') or has_role('ROLE_WELFARE_CLERK')")
     * @param Request $request
     * @param ExServiceman $exServiceman
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function editAction(Request $request, ExServiceman $exServiceman)
    {
        $editForm = $this->createForm(ExServicemanType::class, $exServiceman, ['office' => $this->getOffice()]);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $exServiceman->setGender($this->genderRepo()->find($exServiceman->getGender()));

            $em->flush();

            return $this->redirectToRoute('ex_serviceman_list');
        }

        return $this->render('@Personnel/ExServiceman/form.html.twig', array(
            'pageTitle' => 'Edit Ex-serviceman',
            'form' => $editForm->createView(),
            'receivedFunds' => $exServiceman->getReceivedFunds()
        ));
    }

    /**
     * @Route("/view/{id}", name="ex_serviceman_view")
     * @param ExServiceman $exServiceman
     * @Security("has_role('ROLE_USER') or has_role('ROLE_WELFARE_CLERK')")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function viewAction(ExServiceman $exServiceman)
    {
        return $this->render('PersonnelBundle:ExServiceman:show.html.twig', array(
            'personnel' => $exServiceman,
        ));
    }

    /**
     * @Route("/ex_cervice_man_personal_no_auto_complete", name="ex_cervice_man_personal_no_auto_complete", options={"expose"=true})
     * @param Request $request
     * @return JsonResponse
     */
    public function personalNoAutoCompleteAction(Request $request)
    {
        $personalNo = $this->getDoctrine()->getRepository('PersonnelBundle:ExServiceman')
            ->personalNo();

        /** @var $personalNoQuery */
        return new JsonResponse($personalNo);
    }
}
