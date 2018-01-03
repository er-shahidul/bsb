<?php

namespace AccountBundle\Controller;

use AccountBundle\Entity\AccountIntegration;
use AccountBundle\Entity\FundType;
use AccountBundle\Form\SanctionMapperForm;
use AccountBundle\Manager\AccountIntegrationManager;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\Request;

/**
 * AccountIntegrationController controller.
 *
 * @Route("integration")
 */
class AccountIntegrationController extends AccountBaseController
{
    /**
     *
     * @Route("/edit/{id}/{fundTypeId}", name="account_integration_edit", defaults={"fundTypeId"=null}, options={"expose"=true})
     * @Method({"GET", "POST"})
     * @Security("is_granted('edit:account_integration_workflow', accountIntegration) and is_granted('SAME_OFFICE', accountIntegration)")
     * @param Request $request
     * @param AccountIntegration $accountIntegration
     * @param FundType|null $fundType
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     * @ParamConverter("fundType", class="AccountBundle:FundType", options={"id" = "fundTypeId"})
     */
    public function editAction(Request $request, AccountIntegration $accountIntegration, FundType $fundType = null)
    {
        $sanction = $accountIntegration->getData();
        $manager = $this->get(AccountIntegrationManager::class);

        if ($fundType) {
            $sanction->setFundType($fundType->getId());
        }

        $editForm = $this->createForm(SanctionMapperForm::class, $sanction, [
            'fundTypeOption' => $this->fundTypeRepo()->getOfficeFundTypeAsArray(!$this->getOffice()->isBasb())
        ]);

        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $manager->save($accountIntegration, $sanction, $request->request->all(), $editForm->getData());

            $this->addFlash('success', 'Update Successfully');

            $mytask = $this->getRepository('DevnetWorkflowBundle:UserTask')->findOneBy(['entity' => AccountIntegration::class, 'refId' => $accountIntegration->getId()]);
            return $this->redirectToRoute('devnet_workflow_my_workflow', ['id' => $mytask->getId()]);
        }

        $manager->prepareVoucherDetail($sanction);
        $data = [
            'ai' => $accountIntegration,
            'sanction' => $sanction,
            'form' => $editForm->createView(),
            'bankAccounts' => $this->bankAccountRepo()->getBankAccountByOffice($this->getOffice(), $sanction->getFundType()),
        ];

        if ($sanction->getFundType()) {
            $data['fundHeads'] = $this->fundHead()->fundHeadByFundType($this->fundTypeRepo()->find($sanction->getFundType()), $this->getOffice()->getOfficeType());
        }

        return $this->render('@Account/AccountIntegration/update.html.twig', $data);
    }
}