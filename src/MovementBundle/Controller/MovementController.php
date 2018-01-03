<?php

namespace MovementBundle\Controller;

use AppBundle\Controller\BaseController;
use Doctrine\ORM\QueryBuilder;
use MovementBundle\Datatables\DirectorMovementDatatable;
use MovementBundle\Datatables\GeneralMovementDatatable;
use MovementBundle\Datatables\SecretaryMovementDatatable;
use MovementBundle\Entity\DirectorMovement;
use MovementBundle\Entity\GeneralMovement;
use MovementBundle\Entity\Movement;
use MovementBundle\Entity\SecretaryMovement;
use MovementBundle\Form\MovementType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sg\DatatablesBundle\Datatable\DatatableInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class MovementController extends BaseController
{
    /**
     * @Route("/general/list", name="movement_general_list")
     * @param Request $request
     *
     * @return \Sg\DatatablesBundle\Datatable\AbstractDatatable|\Symfony\Component\HttpFoundation\Response
     */
    public function generalListAction(Request $request)
    {
        /** @var DatatableInterface|Response $datatable */
        $datatable = $this->prepareDatatable(GeneralMovementDatatable::class, $request->isXmlHttpRequest(), function ($qb) {
            /** @var QueryBuilder $qb */
            if ($this->getOffice()) {
                $qb->andWhere("generalmovement.office = :office");
                $qb->setParameter('office', $this->getOffice());
            }
        });

        return $this->movementList($request, $datatable, 'movement_general_create', 'General');
    }

    /**
     * @Route("/director/list", name="movement_director_list")
     * @param Request $request
     * @Security("has_role('ROLE_ESTABLISHMENT_CLERK') or has_role('ROLE_DIRECTOR')")
     *
     * @return \Sg\DatatablesBundle\Datatable\AbstractDatatable|\Symfony\Component\HttpFoundation\Response
     */
    public function directorListAction(Request $request)
    {
        /** @var DatatableInterface|Response $datatable */
        $datatable = $this->prepareDatatable(DirectorMovementDatatable::class, $request->isXmlHttpRequest(), function ($qb) {
            /** @var QueryBuilder $qb */
            if ($this->getOffice()) {
                $qb->andWhere("directormovement.office = :office");
                $qb->setParameter('office', $this->getOffice());
            }
        });

        return $this->movementList($request, $datatable, 'movement_director_create', 'Director');
    }

    /**
     * @Route("/secretary/list", name="movement_secretary_list")
     * @param Request $request
     *
     * @return \Sg\DatatablesBundle\Datatable\AbstractDatatable|\Symfony\Component\HttpFoundation\Response
     */
    public function secretaryListAction(Request $request)
    {
        /** @var DatatableInterface|Response $datatable */
        $datatable = $this->prepareDatatable(SecretaryMovementDatatable::class, $request->isXmlHttpRequest(), function ($qb) {
            /** @var QueryBuilder $qb */
            if ($this->getOffice() && strtolower($this->getOffice()->getOfficeType()->getName()) == 'dasb') {
                $qb->andWhere("secretarymovement.office = :office");
                $qb->setParameter('office', $this->getOffice());
            }
        });

        return $this->movementList($request, $datatable, 'movement_secretary_create', 'Secretary');
    }

    /**
     * @Route("/general/create", name="movement_general_create")
     * @param Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function generalCreateAction(Request $request)
    {
        return $this->createMovement($request, new GeneralMovement(), 'movement_general_list', 'General Movement Create');
    }

    /**
     * @Route("/general/{id}/edit", name="movement_general_edit")
     * @param Request         $request
     * @param GeneralMovement $movement
     * @Security("is_granted('edit:general_movement', movement)")
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function generalEditAction(Request $request, GeneralMovement $movement)
    {
        return $this->editMovement($request, $movement, 'movement_general_list', 'General Movement Edit');
    }

    /**
     * @Route("/view/{id}", name="movement_view")
     * @param Movement $movement
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function viewAction(Movement $movement)
    {
        $this->denyAccessBasedOnAccessPrivilege($movement);

        return $this->render('@Movement/Movement/show.html.twig', array(
            'movement'      => $movement,
            'movement_type' => $movement->getType(),
            'entityClass'   => get_class($movement)
        ));
    }

    /**
     * @Route("/director/create", name="movement_director_create")
     * @param Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function directorCreateAction(Request $request)
    {
        return $this->createMovement($request, new DirectorMovement(), 'movement_director_list', 'Director Movement Create');
    }

    /**
     * @Route("/director/{id}/edit", name="movement_director_edit")
     * @param Request          $request
     * @param DirectorMovement $movement
     * @Security("is_granted('edit:director_movement', movement)")
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function editDirectorMovementAction(Request $request, DirectorMovement $movement)
    {
        return $this->editMovement($request, $movement, 'movement_director_list', 'Director Movement Edit');
    }

    /**
     * @Route("/secretary/create", name="movement_secretary_create")
     * @param Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function secretaryCreateAction(Request $request)
    {
        return $this->createMovement($request, new SecretaryMovement(), 'movement_secretary_list', 'Secretary Movement Create');
    }

    /**
     * @Route("/secretary/{id}/edit", name="movement_secretary_edit")
     * @param Request           $request
     * @param SecretaryMovement $movement
     * @Security("is_granted('edit:secretary_movement', movement)")
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function secretaryEditAction(Request $request, SecretaryMovement $movement)
    {
        return $this->editMovement($request, $movement, 'movement_secretary_list', 'Secretary Movement Edit');
    }

    /**
     * @param Request $request
     * @param         $movement
     * @param         $redirect
     * @param         $title
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function createMovement(Request $request, Movement $movement, $redirect, $title)
    {
        $form = $this->createForm(MovementType::class, $movement, ['office' => $this->getOffice()]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $movement->setOffice($this->getOffice());
            $em->persist($movement);
            $em->flush();

            return $this->redirectToRoute($redirect);
        }
        $offices = $this->getDoctrine()->getRepository('AppBundle:Office')
            ->officeAutoComplete();

        return $this->render('@Movement/Movement/form.html.twig', array(
            'pageTitle' => $title,
            'offices'   => json_encode($offices),
            'form'      => $form->createView(),
        ));
    }

    /**
     * @param Request  $request
     * @param Movement $movement
     * @param          $redirect
     * @param          $title
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function editMovement(Request $request, Movement $movement, $redirect, $title)
    {
        $form = $this->createForm(MovementType::class, $movement, ['office' => $this->getOffice()]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();

            $em->flush();

            return $this->redirectToRoute($redirect);
        }

        $offices = $this->getDoctrine()->getRepository('AppBundle:Office')
            ->officeAutoComplete();

        return $this->render('@Movement/Movement/form.html.twig', array(
            'pageTitle' => $title,
            'offices'   => json_encode($offices),
            'form'      => $form->createView(),
        ));
    }

    /**
     * @param Request $request
     * @param         $datatable
     * @param         $redirect
     * @param         $title
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function movementList(Request $request, $datatable, $redirect, $title)
    {
        if ($request->isXmlHttpRequest()) {
            return $datatable;
        }

        return $this->render('@Movement/Movement/list.html.twig', array(
            'datatable'  => $datatable,
            'pageTitle'  => $title,
            'createLink' => $this->generateUrl($redirect),
        ));
    }

    private function denyAccessBasedOnAccessPrivilege(Movement $movement)
    {
        switch (TRUE) {
            case $movement instanceof DirectorMovement:
                if (!($this->isGranted('ROLE_ESTABLISHMENT_CLERK') || $this->isGranted('ROLE_DIRECTOR'))) {
                    throw $this->createAccessDeniedException('Access denied');
                }
                break;
            case $movement instanceof GeneralMovement:
                $this->denyAccessUnlessGranted('SAME_OFFICE', $movement);
                break;
        }
    }
}
