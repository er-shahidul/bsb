<?php

namespace AccountBundle\Controller;

use AccountBundle\Datatables\FundHeadDatatable;
use AccountBundle\Entity\FundHead;
use AccountBundle\Form\FundHeadForm;
use AppBundle\Controller\BaseController;
use Doctrine\ORM\QueryBuilder;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\Request;

/**
 * Fundhead controller.
 *
 * @Route("fundhead")
 */
class FundHeadController extends BaseController
{
    /**
     * Lists all fundHead entities.
     *
     * @Route("/", name="account_fundhead_index")
     * @Security("has_role('ROLE_ADMIN')")
     * @Method("GET")
     */
    public function indexAction(Request $request)
    {
        $datatable = $this->prepareDatatable(FundHeadDatatable::class, $request->isXmlHttpRequest(), function ($qb){
            /** @var QueryBuilder $qb */
            $qb->orderBy('officeType.name', 'DESC');
            $qb->addOrderBy('fundType.name', 'ASC');
        });

        if ($request->isXmlHttpRequest()) {
            return $datatable;
        }

        return $this->render('@Account/FundHead/index.html.twig', array(
            'datatable' => $datatable,
        ));
    }

    /**
     * Creates a new fundHead entity.
     *
     * @Route("/new", name="account_fundhead_new")
     * @Method({"GET", "POST"})
     * @Security("has_role('ROLE_ADMIN')")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function newAction(Request $request)
    {
        $fundHead = new Fundhead();
        $form = $this->createForm(FundHeadForm::class, $fundHead);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($fundHead);
            $em->flush();

            return $this->redirectToRoute('account_fundhead_index', array('id' => $fundHead->getId()));
        }

        return $this->render('@Account/FundHead/new.html.twig', array(
            'fundHead' => $fundHead,
            'form' => $form->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing fundHead entity.
     *
     * @Route("/{id}/edit", name="account_fundhead_edit")
     * @Method({"GET", "POST"})
     * @Security("has_role('ROLE_ADMIN')")
     * @param Request $request
     * @param FundHead $fundHead
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function editAction(Request $request, FundHead $fundHead)
    {
        $editForm = $this->createForm(FundHeadForm::class, $fundHead);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('account_fundhead_index');
        }

        return $this->render('@Account/FundHead/edit.html.twig', array(
            'fundHead' => $fundHead,
            'edit_form' => $editForm->createView()
        ));
    }
}
