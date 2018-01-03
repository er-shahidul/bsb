<?php

namespace Devnet\PolicyManagerBundle\Controller;

use Devnet\PolicyManagerBundle\Manager\PolicyManager;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('DevnetPolicyManagerBundle:Default:index.html.twig', array(
            'forms' => $this->getPolicyManager()->getGroupPolicyForms()
        ));
    }

    public function savePolicyAction($key, Request $request){
        $form = $this->getPolicyManager()->getGroupPolicyForm($key);
        $form->handleRequest($request);

        if($form->isValid()) {
            $this->getPolicyManager()->saveGroupData($key, $form->getData());
        }

        $this->addFlash('success', $this->getPolicyManager()->getGroupPolicyLabel($key) . ' data updated!');
        return $this->redirect($this->generateUrl('devnet_policy_manager_form'));
    }

    /**
     * @return PolicyManager|object
     */
    protected function getPolicyManager()
    {
        return $this->get(PolicyManager::class);
    }

}
