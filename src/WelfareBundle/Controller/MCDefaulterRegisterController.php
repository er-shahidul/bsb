<?php

namespace WelfareBundle\Controller;

use AppBundle\Controller\BaseController;
use BoardMeetingBundle\Entity\BoardMeeting;
use Doctrine\DBAL\Types\BooleanType;
use Doctrine\DBAL\Types\Type;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sg\DatatablesBundle\Datatable\DatatableInterface;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use UserBundle\Entity\User;
use WelfareBundle\Datatables\MCDefaulterPaymentDatatable;
use WelfareBundle\Datatables\MCDefaulterDatatable;
use WelfareBundle\Datatables\MCDefaulterRegisterDatatable;
use WelfareBundle\Datatables\MicroCreditPaymentDatatable;
use WelfareBundle\Entity\MCDefaulter;
use WelfareBundle\Entity\MCDefaulterRegister;
use WelfareBundle\Entity\MicroCreditApplication;
use WelfareBundle\Entity\MCInstallment;
use WelfareBundle\Entity\MicroCreditPayment;
use WelfareBundle\Form\MCDefaulterRegisterForm;
use WelfareBundle\Form\PaymentReceiveForm;
use WelfareBundle\Manager\MCPaymentManager;

class MCDefaulterRegisterController extends BaseController
{
    /**
     * @Security("is_granted('ROLE_USER')")
     * @Route("/welfare/micro-credit/defaulter-register/", name="welfare_mc_defaulter_register_index")
     * @param Request $request
     * @return DatatableInterface|Response
     */
    public function indexAction(Request $request)
    {
        /** @var DatatableInterface|Response $datatable */
        $datatable = $this->prepareDatatable(MCDefaulterRegisterDatatable::class, $request->isXmlHttpRequest(), function ($qb) {
            /** @var QueryBuilder $qb */
            if ($this->getOffice() && strtolower($this->getOffice()->getOfficeType()->getName()) == 'dasb') {
                $qb->andWhere("mcdefaulterregister.office = :office")->setParameter('office', $this->getOffice());
            }
        });

        if ($request->isXmlHttpRequest()) {
            return $datatable;
        }

        return $this->render('WelfareBundle:MCDefaulterRegister:index.html.twig', array(
            'datatable' => $datatable,
        ));
    }

    /**
     * @Security("is_granted('ROLE_WELFARE_CLERK')")
     * @Route("/welfare/micro-credit/defaulter-register/create/", name="welfare_mc_defaulter_register_create")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     */
    public function createAction(Request $request)
    {
        $register = new MCDefaulterRegister();
        $register->setOffice($this->getOffice());
        $register->setStatus('draft');

        $form = $this->createFormBuilder()
            ->add('save', SubmitType::class, ['attr' => ['class' => 'btn green']])
            ->getForm();

        if ($request->isMethod('POST')) {
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {
                $register->setDefaulterRemarks($request->request->get('hdnDefaulters', ''));
                $this->getDoctrine()->getManager()->persist($register);
                $this->getDoctrine()->getManager()->flush();
                return $this->redirectToRoute('welfare_mc_defaulter_register_index');
            }
        }

        return $this->_renderView($form, $register);
    }

    /**
     * @Security("is_granted('edit:mc_defaulter_register', register)")
     * @Route("/welfare/micro-credit/defaulter-register/{id}/edit", name="welfare_mc_defaulter_register_edit")
     * @param Request $request
     * @param MCDefaulterRegister $register
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     */
    public function editAction(Request $request, MCDefaulterRegister $register)
    {
        if ($register->getOffice() != $this->getOffice()) {
            throw $this->createAccessDeniedException();
        }

        $form = $this->createFormBuilder()
            ->add('save', SubmitType::class, ['attr' => ['class' => 'btn green']])
            ->getForm();

        if ($request->isMethod('POST')) {
            $form->handleRequest($request);
            if ($form->isValid()) {
                $register->setDefaulterRemarks($request->request->get('hdnDefaulters', ''));
                $this->getDoctrine()->getManager()->flush();
                return $this->redirectToRoute('welfare_mc_defaulter_register_index');
            }
        }

        return $this->_renderView($form, $register);
    }

    private function _renderView($form, $register) {

        $isSearchable = false;
        /** @var User $user */
        $user = $this->getUser();
        if ($user->hasRole('ROLE_WELFARE_CLERK')) {
            $isSearchable = true;
        }

        $meetings = $this->getDoctrine()->getManager()->getRepository('BoardMeetingBundle:BoardMeeting')
            ->findBy(['type' => 'micro-credit']);
        return $this->render('WelfareBundle:MCDefaulterRegister:create.html.twig', [
            'form' => $form->createView(),
            'meetings' => $meetings,
            'register' => $register,
            'defaulterListView' => $this->getDefaulterList($register->getDefaulterRemarks()),
            'checkable' => true,
            'isSearchable' => $isSearchable
        ]);
    }

    /**
     * @Security("is_granted('ROLE_USER')")
     * @Route("/welfare/micro-credit/defaulter-register/view/{id}", name="welfare_mc_defaulter_register_view")
     * @param MCDefaulterRegister $register
     * @return Response
     */
    public function viewAction(MCDefaulterRegister $register)
    {
        return $this->render('WelfareBundle:MCDefaulterRegister:view.html.twig', [
            'register' => $register,
            'defaulterListView' => $this->getDefaulterList($register->getDefaulterRemarks(), false)
        ]);
    }

    /**
     * @Security("is_granted('ROLE_USER')")
     * @Route("/welfare/micro-credit/defaulter-register/remove-defaulter/{register}/{defaulter}/", name="welfare_mc_defaulter_register_remove_defaulter")
     * @param MCDefaulterRegister $register
     * @param MCDefaulter $defaulter
     * @return Response
     * @internal param MicroCreditApplication $application
     */
    public function removeItemAction(MCDefaulterRegister $register, MCDefaulter $defaulter)
    {
        $register->removeDefaulter($defaulter);
        $this->getDoctrine()->getManager()->flush();
        $data['register'] = $register;
        return $this->render('WelfareBundle:MCDefaulterRegister:view.html.twig', $data);
    }

    /**
     * @Security("is_granted('ROLE_USER')")
     * @Route("/welfare/micro-credit/defaulter-register/defaulter-list/{id}", name="welfare_mc_defaulter_register_defaulter_list")
     * @param Request $request
     * @param BoardMeeting $meeting
     * @return Response
     */
    public function getDefaulterListByMeeting(Request $request, BoardMeeting $meeting) {
        $results = $this->getDoctrine()->getRepository('WelfareBundle:MicroCreditPayment')->getDefaultersByMeeting($meeting);
        return new Response($this->defaulterListView($results));
    }

    public function getDefaulterList($ids = [], $checkable = true) {
        $results = $this->getDoctrine()->getRepository('WelfareBundle:MicroCreditPayment')->getDefaultersByIds($ids);
        return $this->defaulterListView($results, $checkable);
    }

    public function defaulterListView($results, $checkable = true) {
        $rows = '';
        if (count($results)) {
            foreach ($results as $result) {
                $rows .= $this->renderView('WelfareBundle:MCDefaulterRegister:defaulter_row.html.twig', [
                        'application' => $result[0],
                        'checkable' => $checkable,
                        'totalPayable' => $result['totalPayable']
                    ]
                );
            }
        } else {
            $rows = ' <tr><td colspan="11" class="text-center"></td></tr> ';
        }
        return $this->renderView('WelfareBundle:MCDefaulterRegister:defaulter_table.html.twig', [
            'rows' => $rows,
            'checkable' => $checkable,
        ]);
    }

}
