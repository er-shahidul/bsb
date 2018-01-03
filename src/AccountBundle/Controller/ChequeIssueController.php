<?php

namespace AccountBundle\Controller;

use AccountBundle\Datatables\ChequeIssueDatatable;
use AccountBundle\Entity\ChequeIssue;
use AccountBundle\Entity\FundType;
use AccountBundle\Form\ChequeIssueForm;
use AccountBundle\Manager\ChequeIssueManager;
use Doctrine\ORM\QueryBuilder;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

/**
 * ChequeIssueController controller.
 *
 * @Route("cheque-issue")
 */
class ChequeIssueController extends AccountBaseController
{
    /**
     * @Route("/list", name="account_cheque_issue_list")
     */
    public function indexAction(Request $request)
    {
        $datatable = $this->prepareDatatable(ChequeIssueDatatable::class, $request->isXmlHttpRequest(), function ($qb) {
            /** @var QueryBuilder $qb */
            $qb->andWhere("chequeissue.office = :office");
            $qb->setParameter('office', $this->getUser()->getOffice());
        });

        if ($request->isXmlHttpRequest()) {
            return $datatable;
        }

        return $this->render('@Account/ChequeIssue/index.html.twig', array(
            'datatable' => $datatable,
            'fundTypes' => $this->getFundTypes(),
        ));
    }

    /**
     * @Route("/new/{fundType}", name="account_cheque_issue_create")
     * @Security("is_granted(['ROLE_ACCOUNT_CLERK', 'DASB_USER'])")
     */
    public function newAction(Request $request, FundType $fundType)
    {
        $chequeIssue = new ChequeIssue();
        $chequeIssue->setFundType($fundType);
        $chequeIssue->setOffice($this->getOffice());
        $form = $this->createForm(ChequeIssueForm::class, $chequeIssue, [
            'em' => $this->getDoctrine()->getManager()
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid() && $chequeIssue->getNoteSheetTotalAmount() == $this->chequeIssueRepo()->calculateFundHead($request->request->all())) {
            $this->chequeIssueRepo()->save($chequeIssue);
            $this->chequeIssueRepo()->saveVoucherInformation($chequeIssue, $request->request->all());

            $this->addFlash('success', 'Cheque Issue created successfully');
            return $this->redirectToRoute('account_cheque_issue_list');
        }

        return $this->render('@Account/ChequeIssue/payment.html.twig',
            $this->getCreateUpdateData($chequeIssue, $form)
        );
    }

    /**
     * @Route("/update/{id}", name="account_cheque_issue_update")
     * @Security("is_granted('edit:cheque_issue:draft', chequeIssue) and is_granted('SAME_OFFICE', chequeIssue)")
     */
    public function updateAction(Request $request, ChequeIssue $chequeIssue)
    {
        $form = $this->createForm(ChequeIssueForm::class, $chequeIssue, [
            'em' => $this->getDoctrine()->getManager()
        ]);
        $form->handleRequest($request);

        $this->get(ChequeIssueManager::class)->prepareVoucherDetail($chequeIssue);

        if ($form->isSubmitted() && $form->isValid() && $chequeIssue->getNoteSheetTotalAmount() == $this->chequeIssueRepo()->calculateFundHead($request->request->all())) {

            $this->chequeIssueRepo()->save($chequeIssue);
            $this->chequeIssueRepo()->saveVoucherInformation($chequeIssue, $request->request->all());

            $this->addFlash('success', 'Cheque Issue updated successfully');
            return $this->redirectToRoute('account_cheque_issue_list');
        }

        return $this->render('@Account/ChequeIssue/payment.html.twig',
            $this->getCreateUpdateData($chequeIssue, $form)
        );
    }

    /**
     * @Route("/view/{id}", name="account_cheque_issue_view")
     * @Security("is_granted(['ROLE_ACCOUNT', 'DASB_USER']) and is_granted('SAME_OFFICE', chequeIssue)")
     */
    public function viewAction(ChequeIssue $chequeIssue)
    {
        $this->get(ChequeIssueManager::class)->prepareVoucherDetail($chequeIssue);

        return $this->render('AccountBundle:ChequeIssue:view.html.twig',
            $this->getCreateUpdateData($chequeIssue)
        );
    }

    protected function getCreateUpdateData(ChequeIssue $chequeIssue, $form = null)
    {
        return array(
            'choicesOption' => $this->getRepository('AccountBundle:Payee')->getDropDownOption($chequeIssue->getFundType()),
            'chequeIssue' => $chequeIssue,
            'form' => $form ? $form->createView() : null,
            'fundHeads' => $this->fundHead()->fundHeadByFundType($chequeIssue->getFundType(), $this->getOffice()->getOfficeType()),
            'bankAccounts' => $this->bankAccountRepo()->getBankAccountByOffice($this->getOffice(), $chequeIssue->getFundType()),
            'fundHeadBalance' => $this->get(ChequeIssueManager::class)->getFundHeadBalanceByFundType($chequeIssue->getFundType(), $chequeIssue->getVouchers()),
        );
    }
}