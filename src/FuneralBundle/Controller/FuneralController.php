<?php

namespace FuneralBundle\Controller;

use Doctrine\ORM\QueryBuilder;
use FuneralBundle\Entity\Funeral;
use FuneralBundle\Form\FuneralType;
use AppBundle\Controller\BaseController;
use Symfony\Component\HttpFoundation\Request;
use FuneralBundle\Datatables\FuneralDatatable;
use Symfony\Component\HttpFoundation\Response;
use Sg\DatatablesBundle\Datatable\DatatableInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;


class FuneralController extends BaseController
{
    /**
     * @Route("/list", name="funeral_list")
     * @param Request $request
     * @return \Sg\DatatablesBundle\Datatable\AbstractDatatable|\Symfony\Component\HttpFoundation\Response
     */
    public function funeralListAction(Request $request)
    {
        return $this->funeralList($request, FuneralDatatable::class, 'funeral_create', 'Funeral');
    }

    /**
     * @Route("/create", name="funeral_create")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function funeralCreateAction(Request $request)
    {
        $funeral = new Funeral();
        $em = $this->getDoctrine()->getManager();
        $personalNo = $request->request->get('personal-no');

        if($personalNo) {
            $exServiceman = $em->getRepository('PersonnelBundle:ExServiceman')->findOneBy(array(
                'identityNumber' => $personalNo, 'office' => $this->getOffice()
            ));
            if($exServiceman){
                if(!$exServiceman->isDeceased()){
                    $application = $em->getRepository('FuneralBundle:Funeral')->findOneByExServiceman($exServiceman);
                    $funeralCondition = $application ? $application->getFuneralCondition() : false;
                    if(!$funeralCondition){
                        return $this->renderCreateForm($request, $funeral, 'funeral_list', 'Funeral Create', $em, $exServiceman);
                    }else{
                        $this->flashMsg('error','Already Applied For Funeral');
                    }
                }else{
                    $this->flashMsg('error','This Person Has Already Deceased');
                }
            }else{
                $this->flashMsg('error','This personnel does not belong to '.$this->getOffice()->getName() ." DASB");
            }
        }

        return $this->render('@Funeral/Funeral/create.html.twig', array(
            'pageTitle' => 'Funeral Create',
        ));
    }

    /**
     * @Route("/{id}/edit", name="funeral_edit")
     * @param Request $request
     * @param Funeral $funeral
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function funeralEditAction(Request $request, Funeral $funeral)
    {
        $form = $this->createForm(FuneralType::class, $funeral, ['office' => $this->getOffice(), 'route' => 'funeral_edit']);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();

            $em->flush();

            return $this->redirectToRoute('funeral_list');
        }

        return $this->render('@Funeral/Funeral/form.html.twig', array(
            'pageTitle' => 'Funeral Edit',
            'route' => 'funeral_list',
            'exServiceman' => $funeral->getExServiceman(),
            'form' => $form->createView(),
        ));
    }

    /**
     * @Route("/view/{id}", name="funeral_view")
     * @param Funeral $funeral
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function viewAction(Funeral $funeral)
    {
        return $this->render('@Funeral/Funeral/show.html.twig', array(
            'funeral' => $funeral,
            'entityClass' => get_class($funeral)
        ));
    }

    /**
     * @param Request $request
     * @param $funeralDatatable
     * @param $redirect
     * @param $title
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function funeralList(Request $request, $funeralDatatable, $redirect, $title)
    {
        /** @var DatatableInterface|Response $datatable */
        $datatable = $this->prepareDatatable($funeralDatatable, $request->isXmlHttpRequest(), function ($qb) {
            /** @var QueryBuilder $qb */
            if ($this->getOffice() && strtolower($this->getOffice()->getOfficeType()->getName()) == 'dasb') {
                $qb->andWhere("funeral.office = :office");
                $qb->setParameter('office', $this->getOffice());
            }
        });

        if ($request->isXmlHttpRequest()) {
            return $datatable;
        }

        return $this->render('@Funeral/Funeral/list.html.twig', array(
            'datatable' => $datatable,
            'pageTitle' => $title,
            'createLink' => $this->generateUrl($redirect),
        ));
    }

    /**
     * @param Request $request
     * @param Funeral $funeral
     * @param $redirect
     * @param $title
     * @param $em
     * @param $exServiceman
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function renderCreateForm(Request $request, Funeral $funeral, $redirect, $title, $em, $exServiceman)
    {
        $form = $this->createForm(FuneralType::class, $funeral, ['office' => $this->getOffice(), 'route' => 'funeral_create']);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $funeral->setOffice($this->getOffice());
            $funeral->setExServiceman($exServiceman);

            $em->persist($funeral);
            $em->flush();

            return $this->redirectToRoute($redirect);
        }

        return $this->render('@Funeral/Funeral/form.html.twig', array(
            'pageTitle' => $title,
            'route' => 'funeral_list',
            'exServiceman' => $exServiceman,
            'form' => $form->createView(),
        ));
    }

    /**
     * @param $type
     * @param $msg
     * @return mixed
     */
    public function flashMsg($type, $msg)
    {
        return $this->get('session')->getFlashBag()->add($type, $msg);
    }
}
