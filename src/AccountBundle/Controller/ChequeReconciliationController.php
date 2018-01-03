<?php

namespace AccountBundle\Controller;

use AccountBundle\Datatables\ChequeReconciliationDatatable;
use AccountBundle\Entity\BankAccount;
use AccountBundle\Entity\ChequeReconciliation;
use AccountBundle\Entity\FundType;
use AccountBundle\Entity\Reconciliation;
use AccountBundle\Form\ChequeReconciliationForm;
use BudgetBundle\Datatables\Column\HumanizeTextColumn;
use Doctrine\ORM\QueryBuilder;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\Request;

/**
 * ChequeReconciliationController controller.
 *
 * @Route("reconciliation")
 */
class ChequeReconciliationController extends AccountBaseController
{
    /**
     * @Route("/", name="account_reconciliation_index")
     * @Method("GET")
     * @Security("has_role('ROLE_ACCOUNT') or is_granted('DASB_USER')")
     * @param Request $request
     * @return \Sg\DatatablesBundle\Datatable\AbstractDatatable|\Symfony\Component\HttpFoundation\Response
     */
    public function indexAction(Request $request)
    {
        $datatable = $this->prepareDatatable(ChequeReconciliationDatatable::class, $request->isXmlHttpRequest(), function ($qb) {
            /** @var QueryBuilder $qb */
            $qb->andWhere("chequereconciliation.office = :office");
            $qb->setParameter('office', $this->getUser()->getOffice());
        });

        if ($request->isXmlHttpRequest()) {
            return $datatable;
        }

        return $this->render('@Account/ChequeReconciliation/index.html.twig', array(
            'datatable' => $datatable,
            'fundTypes' => $this->getFundTypes(),
            'reconciliationMonths' => $this->chequeReconciliationRepo()->getAllReconciliationMonth($this->getOffice())
        ));
    }


    /**
     * @Route("/new/{fundType}", name="account_reconciliation_new")
     * @Method({"GET", "POST"})
     * @Security("(has_role('ROLE_ACCOUNT_CLERK') or has_role('ROLE_DASB_CLERK'))")
     * @param Request $request
     * @param BankAccount $fundType
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function newAction(Request $request, FundType $fundType)
    {
        $reconciliationDate = $this->chequeReconciliationRepo()->getReconciliationMonthByFundType($fundType, $this->getOffice());

        if ($recon = $this->chequeReconciliationRepo()->getLastReconciliation($this->getOffice(), $fundType)) {
            $this->addFlash('error', 'Last month reconciliation not approved yet');
            return $this->redirectToRoute('account_reconciliation_index');
        }

        $reconciliation = new ChequeReconciliation();
        $reconciliation->setFundType($fundType);
        $reconciliation->setOffice($this->getOffice());
        $reconciliation->setMonth($reconciliationDate['month']);
        $reconciliation->setYear($reconciliationDate['year']);

        $form = $this->createForm(ChequeReconciliationForm::class, $reconciliation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $this->getDoctrine()->getRepository('AccountBundle:Reconciliation')->save($reconciliation);

            $this->addFlash('success', 'Bank Reconciliation Created Successfully');

            return $this->redirectToRoute('account_reconciliation_index');
        }

        return $this->render('@Account/ChequeReconciliation/create.html.twig', array(
            'reconciliation' => $reconciliation,
            'form' => $form->createView(),
        ));
    }

    /**
     * @Route("/edit/{id}", name="account_reconciliation_edit")
     * @Method({"GET", "POST"})
     * @Security("is_granted('SAME_OFFICE', reconciliation)")
     * @param Request $request
     * @param ChequeReconciliation|Reconciliation $reconciliation
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function editAction(Request $request, ChequeReconciliation $reconciliation)
    {
        if (!$this->isGranted('edit:cheque_reconciliation_workflow', $reconciliation)) {
            $year = (new \DateTime($reconciliation->getYear().'-'.$reconciliation->getMonth()))->format('F Y');
            $this->addFlash('error', sprintf('Reconcile for %s already created and status is %s', $year ,HumanizeTextColumn::humanize($reconciliation->getStatus())));
            return $this->redirectToRoute('account_reconciliation_index');
        }

        $form = $this->createForm(ChequeReconciliationForm::class, $reconciliation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $this->getDoctrine()->getRepository('AccountBundle:Reconciliation')->save($reconciliation);

            $this->addFlash('success', 'Bank Reconciliation Updated Successfully');

            return $this->redirect($request->request->get('_referrer'));
        }

        return $this->render('@Account/ChequeReconciliation/create.html.twig', array(
            'reconciliation' => $reconciliation,
            'form' => $form->createView(),
        ));
    }

    /**
     * @Route("/view/{id}", name="account_reconciliation_view")
     * @Security("is_granted(['ROLE_ACCOUNT', 'DASB_USER']) and is_granted('SAME_OFFICE', reconciliation)")
     * @param ChequeReconciliation|Reconciliation $reconciliation
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function viewAction(ChequeReconciliation $reconciliation)
    {
        return $this->render('@Account/ChequeReconciliation/view.html.twig', array(
            'reconciliation' => $reconciliation,
        ));
    }
}
