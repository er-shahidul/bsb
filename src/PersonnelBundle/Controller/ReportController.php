<?php

namespace PersonnelBundle\Controller;

use Libs\Mpdf\MpdfFactory;
use Symfony\Component\HttpFoundation\Request;
use PersonnelBundle\Form\ReportForm\ExServicemanReportForm;
use PersonnelBundle\Form\ReportForm\ServingMilitaryReportForm;
use PersonnelBundle\Form\ReportForm\ServingCivilianReportForm;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

/**
 * @Route("/report")
 */
class ReportController extends PersonnelBaseController
{
    /**
     * @Route("/personnel-ex-serviceman", name="personnel_report_personnel_ex_serviceman")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function personnelExServicemanAction(Request $request)
    {
        $form = $this->createForm(ExServicemanReportForm::class);
        $data = $request->get($form->getName());

        $form->submit($data);
        $repo = $this->exServicemanRepo();
        $exServiceMans = $repo->getExServicemanData($data);

            if ($request->isMethod('POST')) {
                $exServiceMans = $repo->getExServicemanData($request->query->get('search'));
                $mpdf = MpdfFactory::create();
                $html = $this->renderView('@Personnel/Report/ex-serviceman-pdf.html.twig', array(
                    'exServiceMans' =>$exServiceMans,
                    'title' => $_POST['title'],
                ));
                $mpdf->WriteHTML($html);
                $mpdf->Output("Ex-Serviceman.pdf", 'I');
            }

        return $this->render('@Personnel/Report/ex-serviceman.html.twig', array(
            'form' => $form->createView(),
            'exServiceMans' =>$exServiceMans,
            'data' => $data
        ));
    }

    /**
     * @Route("/personnel-serving-military", name="personnel_report_personnel_serving_military")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function personnelServingMilitaryAction(Request $request)
    {
        $form = $this->createForm(ServingMilitaryReportForm::class);
        $data = $request->get($form->getName());

        $form->submit($data);
        $repo = $this->servingMilitaryRepo();
        $servingMilitares = $repo->getServingMilitaryData($data);

        if ($request->isMethod('POST')) {
                $servingMilitares = $repo->getServingMilitaryData($request->query->get('search'));
                $mpdf = MpdfFactory::create();
                $html = $this->renderView('@Personnel/Report/serving-military-pdf.html.twig', array(
                    'servingMilitares' =>$servingMilitares,
                    'title' => $_POST['title'],
                ));
                $mpdf->WriteHTML($html);
                $mpdf->Output("Serving Military.pdf", 'I');
            }

        return $this->render('@Personnel/Report/serving-military.html.twig', array(
            'form' => $form->createView(),
            'servingMilitares' =>$servingMilitares,
            'data' => $data
        ));
    }

    /**
     * @Route("/personnel-serving-civilian", name="personnel_report_personnel_serving_civilian")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function personnelServingCivilianAction(Request $request)
    {
        $form = $this->createForm(ServingCivilianReportForm::class);
        $data = $request->get($form->getName());

        $form->submit($data);
        $repo = $this->servingCivilianRepo();
        $servingCivilians = $repo->getServingCivilianData($data);

        if ($request->isMethod('POST')) {
            $servingCivilians = $repo->getServingCivilianData($request->query->get('search'));
            $mpdf = MpdfFactory::create();
            $html = $this->renderView('@Personnel/Report/serving-civilian-pdf.html.twig', array(
                'servingCivilians' =>$servingCivilians,
                'title' => $_POST['title'],
            ));
            $mpdf->WriteHTML($html);
            $mpdf->Output("Serving Civilian.pdf", 'I');
        }

        return $this->render('@Personnel/Report/serving-civilian.html.twig', array(
            'form' => $form->createView(),
            'servingCivilians' =>$servingCivilians,
            'data' => $data
        ));
    }
}