<?php

namespace LeaveBundle\Controller;

use LeaveBundle\Entity\Leave;
use Doctrine\ORM\QueryBuilder;
use LeaveBundle\Form\LeaveType;
use LeaveBundle\Entity\GeneralLeave;
use LeaveBundle\Entity\DirectorLeave;
use LeaveBundle\Entity\CivilianLeave;
use LeaveBundle\Entity\MilitaryLeave;
use LeaveBundle\Entity\SecretaryLeave;
use AppBundle\Controller\BaseController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use LeaveBundle\Datatables\GeneralLeaveDatatable;
use LeaveBundle\Datatables\MilitaryLeaveDatatable;
use LeaveBundle\Datatables\CivilianLeaveDatatable;
use LeaveBundle\Datatables\DirectorLeaveDatatable;
use LeaveBundle\Datatables\SecretaryLeaveDatatable;
use Sg\DatatablesBundle\Datatable\DatatableInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

class LeaveController extends BaseController
{
    /**
     * @Route("/general/list", name="leave_general_list")
     * @param Request $request
     * @return \Sg\DatatablesBundle\Datatable\AbstractDatatable|\Symfony\Component\HttpFoundation\Response
     */
    public function generalListAction(Request $request)
    {
        /** @var DatatableInterface|Response $datatable */
        $datatable = $this->prepareDatatable($this->getDateTableForGeneral(), $request->isXmlHttpRequest(), function ($qb) {
            /** @var QueryBuilder $qb */
            if ($this->getOffice()) {
                $qb->andWhere($this->getTableForGeneral()."= :office");
                $qb->setParameter('office', $this->getOffice());
            }
        });

        return $this->leaveList($request, $datatable, 'leave_general_create', $this->getTitleForGeneral());
    }

    /**
     * @Route("/director/list", name="leave_director_list")
     * @param Request $request
     * @Security("has_role('ROLE_ESTABLISHMENT_CLERK') or has_role('ROLE_DIRECTOR')")
     * @return \Sg\DatatablesBundle\Datatable\AbstractDatatable|\Symfony\Component\HttpFoundation\Response
     */
    public function directorListAction(Request $request)
    {
        /** @var DatatableInterface|Response $datatable */
        $datatable = $this->prepareDatatable(DirectorLeaveDatatable::class, $request->isXmlHttpRequest(), function ($qb) {
            /** @var QueryBuilder $qb */
            if ($this->getOffice()) {
                $qb->andWhere("directorleave.office = :office");
                $qb->setParameter('office', $this->getOffice());
            }
        });

        return $this->leaveList($request, $datatable, 'leave_director_create', 'Director');
    }

    /**
     * @Route("/secretary/list", name="leave_secretary_list")
     * @param Request $request
     * @return \Sg\DatatablesBundle\Datatable\AbstractDatatable|\Symfony\Component\HttpFoundation\Response
     */
    public function secretaryListAction(Request $request)
    {
        /** @var DatatableInterface|Response $datatable */
        $datatable = $this->prepareDatatable(SecretaryLeaveDatatable::class, $request->isXmlHttpRequest(), function ($qb) {
            /** @var QueryBuilder $qb */
            if ($this->getOffice() && strtolower($this->getOffice()->getOfficeType()->getName()) == 'dasb') {
                $qb->andWhere("secretaryleave.office = :office");
                $qb->setParameter('office', $this->getOffice());
            }
        });

        return $this->leaveList($request, $datatable, 'leave_secretary_create', 'Secretary');
    }

    /**
     * @Route("/civilian/list", name="leave_civilian_list")
     * @param Request $request
     * @Security("has_role('ROLE_ESTABLISHMENT_CLERK') or has_role('ROLE_DIRECTOR')")
     * @return \Sg\DatatablesBundle\Datatable\AbstractDatatable|\Symfony\Component\HttpFoundation\Response
     */
    public function civilianListAction(Request $request)
    {
        /** @var DatatableInterface|Response $datatable */
        $datatable = $this->prepareDatatable(CivilianLeaveDatatable::class, $request->isXmlHttpRequest(), function ($qb) {
            /** @var QueryBuilder $qb */
            if ($this->getOffice()) {
                $qb->andWhere("civilianleave.office = :office");
                $qb->setParameter('office', $this->getOffice());
            }
        });

        return $this->leaveList($request, $datatable, 'leave_civilian_create', 'Civilian');
    }

    /**
     * @Route("/general/create", name="leave_general_create")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function generalCreateAction(Request $request)
    {
        return $this->createLeave($request, $this->getEntityForGeneral(), 'leave_general_list', $this->getTitleForGeneral().' Leave Create', $this->getServingTypeForGeneralLeave());
    }

    /**
     * @Route("/director/create", name="leave_director_create")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function directorCreateAction(Request $request)
    {
        return $this->createLeave($request, new DirectorLeave(), 'leave_director_list', 'Director Leave Create', null);
    }

    /**
     * @Route("/secretary/create", name="leave_secretary_create")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function secretaryCreateAction(Request $request)
    {
        return $this->createLeave($request, new SecretaryLeave(), 'leave_secretary_list', 'Secretary Leave Create', null);
    }

    /**
     * @Route("/civilian/create", name="leave_civilian_create")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function civilianCreateAction(Request $request)
    {
        return $this->createLeave($request, new CivilianLeave(), 'leave_civilian_list', 'Civilian Leave Create', 1);
    }

    /**
     * @Route("/general/{id}/edit", name="leave_general_edit")
     * @param Request $request
     * @param GeneralLeave $leave
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     */
    public function generalEditAction(Request $request, GeneralLeave $leave)
    {
        return $this->editLeave($request, $leave, 'leave_general_list', 'General Leave Edit', $this->getServingTypeForGeneralLeave());
    }

    /**
     * @Route("/military/{id}/edit", name="leave_military_edit")
     * @param Request $request
     * @param MilitaryLeave $leave
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     */
    public function militaryEditAction(Request $request, MilitaryLeave $leave)
    {
        return $this->editLeave($request, $leave, 'leave_general_list', 'Military Leave Edit', $this->getServingTypeForGeneralLeave());
    }

    /**
     * @Route("/director/{id}/edit", name="leave_director_edit")
     * @param Request $request
     * @param DirectorLeave $leave
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function directorEditAction(Request $request, DirectorLeave $leave)
    {
        return $this->editLeave($request, $leave, 'leave_director_list', 'Director Leave Edit', null);
    }

    /**
     * @Route("/secretary/{id}/edit", name="leave_secretary_edit")
     * @param Request $request
     * @param SecretaryLeave $leave
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function secretaryEditAction(Request $request, SecretaryLeave $leave)
    {
        return $this->editLeave($request, $leave, 'leave_secretary_list', 'Secretary Leave Edit', null);
    }

    /**
     * @Route("/civilian/{id}/edit", name="leave_civilian_edit")
     * @param Request $request
     * @param CivilianLeave $leave
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function civilianEditAction(Request $request, CivilianLeave $leave)
    {
        return $this->editLeave($request, $leave, 'leave_civilian_list', 'Civilian Leave Edit', 1);
    }

    /**
     * @Route("/view/{id}", name="leave_view")
     * @param Leave $leave
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function viewAction(Leave $leave)
    {
        return $this->render('@Leave/Leave/show.html.twig', array(
            'leave' => $leave,
            'leave_type' => $leave->getType(),
            'entityClass' => get_class($leave)
        ));
    }

    /**
     * @param Request $request
     * @param Leave $leave
     * @param $redirect
     * @param $title
     * @param $servingType
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     * @internal param $leave
     */
    public function createLeave(Request $request, Leave $leave, $redirect, $title, $servingType)
    {
        $form = $this->createForm(LeaveType::class, $leave, ['office' => $this->getOffice(), 'servingType' => $servingType]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $leave->setOffice($this->getOffice());
            $em->persist($leave);
            $em->flush();

            return $this->redirectToRoute($redirect);
        }
        $offices = $this->getDoctrine()->getRepository('AppBundle:Office')
                    ->officeAutoComplete();

        return $this->render('@Leave/Leave/form.html.twig', array(
            'pageTitle' => $title,
            'offices' => json_encode($offices),
            'form' => $form->createView(),
            'servingType' => $servingType,
            'leave' => $leave,
        ));
    }

    /**
     * @param Request $request
     * @param Leave $leave
     * @param $redirect
     * @param $title
     * @param $servingType
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     */
    public function editLeave(Request $request, Leave $leave, $redirect, $title, $servingType)
    {
        $form = $this->createForm(LeaveType::class, $leave, ['office' => $this->getOffice(), 'servingType' => $servingType]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();

            $em->flush();

            return $this->redirectToRoute($redirect);
        }

        $offices = $this->getDoctrine()->getRepository('AppBundle:Office')
            ->officeAutoComplete();

        return $this->render('@Leave/Leave/form.html.twig', array(
            'pageTitle' => $title,
            'offices' => json_encode($offices),
            'form' => $form->createView(),
            'servingType' => $servingType,
            'leave' => $leave,
        ));
    }

    /**
     * @param Request $request
     * @param $datatable
     * @param $redirect
     * @param $title
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function leaveList(Request $request, $datatable, $redirect, $title)
    {
        if ($request->isXmlHttpRequest()) {
            return $datatable;
        }

        return $this->render('@Leave/Leave/list.html.twig', array(
            'datatable' => $datatable,
            'pageTitle' => $title,
            'createLink' => $this->generateUrl($redirect),
        ));
    }

    public function getServingTypeForGeneralLeave()
    {
        return $this->getOffice()->isBasb() ? LeaveType::SERVING_TYPE_MILITARY : null;
    }

    public function getDateTableForGeneral()
    {
        return $this->getOffice()->isBasb() ? MilitaryLeaveDatatable::class : GeneralLeaveDatatable::class;
    }

    private function getTableForGeneral()
    {
        return $this->getOffice()->isBasb() ? "militaryleave.office" : "generalleave.office";
    }

    private function getEntityForGeneral()
    {
        return $this->getOffice()->isBasb() ? new MilitaryLeave() : new GeneralLeave();
    }

    private function getTitleForGeneral()
    {
        return $this->getOffice()->isBasb() ? "Military" : "General";
    }
}
