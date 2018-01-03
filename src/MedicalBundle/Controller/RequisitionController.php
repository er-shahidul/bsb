<?php

namespace MedicalBundle\Controller;

use MedicalBundle\Datatables\RequisitionDatatable;
use MedicalBundle\Entity\Dispensary;
use MedicalBundle\Entity\Requisition;
use MedicalBundle\Entity\RequisitionDetail;
use MedicalBundle\Form\DataTransformer\DispensaryTransformer;
use MedicalBundle\Form\RequisitionForm;
use MedicalBundle\Manager\DispensaryManager;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Route("requisition")
 */
class RequisitionController extends BaseMedicalController
{
    /**
     * @Route("/list", name="medical_requisition_list")
     * @Security("is_granted('DASB_USER')")
     */
    public function indexAction(Request $request)
    {
        /** @var DatatableInterface|Response $datatable */
        $datatable = $this->prepareDatatable(RequisitionDatatable::class, $request->isXmlHttpRequest(), function ($qb) {
            /** @var QueryBuilder $qb */
            /** Default Filter */
            $qb->andWhere("requisition.office = :office");
            $qb->setParameter('office', $this->getOffice());
        });

        if ($request->isXmlHttpRequest()) {
            return $datatable;
        }

        return $this->render('MedicalBundle:Requisition:index.html.twig', [
            'datatable' => $datatable,
            'dispensariesForRequisition' => $this->getDispensaryRepo()->getAvailableDispensaryForRequisition($this->getOffice())
        ]);
    }

    /**
     * @Route("/create/{id}", name="medical_requisition_create", defaults={"id" = null})
     * @Security("is_granted('ROLE_DASB_CLERK')")
     */
    public function newAction(Request $request, Dispensary $dispensary = null)
    {
        $requisition = new Requisition();

        if ($dispensary) {
            $this->denyAccessUnlessGranted('SAME_OFFICE', $dispensary);
            $requisition->setDispensary($dispensary);
        }

        $form = $this->prepareRequisitionForm($requisition);

        $form->handleRequest($request);

        if ($form->isValid()) {
            $this->getRequisitionRepo()->save($requisition);

            $this->addFlash('success', 'Requisition has been created successfully');
            return $this->redirectToRoute('medical_requisition_list');
        }

        return $this->render('@Medical/Requisition/create.html.twig', [
            'form' => $form->createView(),
            'medicines' => $this->getMedicineRepo()->getMedicinesAsArray(),
            'stocks' => $this->getStockRepo()->getStocksAsArray($requisition->getDispensary())
        ]);
    }

    /**
     * @Route("/update/{id}", name="medical_requisition_update")
     * @Security("is_granted('edit:medical_requisition_workflow', requisition)")
     */
    public function updateAction(Requisition $requisition, Request $request)
    {
        $m = $this->get(DispensaryManager::class);
        $m->setOffice($this->getOffice());
        $form = $this->createForm(RequisitionForm::class, $requisition, [
            'dispensaryTransformer' => new DispensaryTransformer($this->get('doctrine.orm.entity_manager')),
            'dispensaryChoices' => $m->getDispensaryAsArrayForRequisition($requisition->getDispensary())
        ]);

        $form->handleRequest($request);
        if ($form->isValid()) {
            $this->getRequisitionRepo()->save($requisition);

            $this->addFlash('success', 'Requisition has been updated successfully');
            return $this->redirect($request->request->get('_referrer'));
        }

        return $this->render('@Medical/Requisition/create.html.twig', [
            'form' => $form->createView(),
            'medicines' => $this->getMedicineRepo()->getMedicinesAsArray(),
            'stocks' => $this->getStockRepo()->getStocksAsArray($requisition->getDispensary())
        ]);
    }

    /**
     * @Route("/view/{id}", name="medical_requisition_view")
     */
    public function viewAction(Requisition $requisition)
    {
        return $this->render('@Medical/Requisition/view.html.twig', [
            'requisition' => $requisition,
            'medicines' => $this->getMedicineRepo()->getMedicinesAsArray(),
        ]);
    }

    /**
     * @return \Symfony\Component\Form\Form
     */
    protected function prepareRequisitionForm(Requisition $requisition)
    {
        $requisition->setOffice($this->getOffice());
        foreach ($this->getMedicineRepo()->getMedicines() as $medicine) {
            $requisitionDetail = new RequisitionDetail();
            $requisitionDetail->setMedicine($medicine);
            $requisitionDetail->setRequisition($requisition);
            $this->getDoctrine()->getManager()->persist($requisitionDetail);
            $requisition->addRequisitionDetail($requisitionDetail);
        }

        $m = $this->get(DispensaryManager::class);
        $m->setOffice($this->getOffice());

        return $this->createForm(RequisitionForm::class, $requisition, [
            'dispensaryTransformer' => new DispensaryTransformer($this->get('doctrine.orm.entity_manager')),
            'dispensaryChoices' => $m->getDispensaryAsArrayForRequisition()
        ]);
    }

}