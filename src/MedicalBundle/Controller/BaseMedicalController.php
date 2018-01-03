<?php

namespace MedicalBundle\Controller;

use AppBundle\Controller\BaseController;

class BaseMedicalController extends BaseController
{
    /**
     * @return \Doctrine\Common\Persistence\ObjectRepository|\MedicalBundle\Repository\MedicineRepository
     */
    public function getMedicineRepo()
    {
        return $this->getDoctrine()->getRepository('MedicalBundle:Medicine');
    }

    /**
     * @return \Doctrine\Common\Persistence\ObjectRepository|\MedicalBundle\Repository\RequisitionRepository
     */
    public function getRequisitionRepo()
    {
        return $this->getDoctrine()->getRepository('MedicalBundle:Requisition');
    }

    /**
     * @return \Doctrine\Common\Persistence\ObjectRepository|\MedicalBundle\Repository\RequisitionDetailRepository
     */
    public function getRequisitionDetailRepo()
    {
        return $this->getDoctrine()->getRepository('MedicalBundle:RequisitionDetail');
    }

    /**
     * @return \Doctrine\Common\Persistence\ObjectRepository|\MedicalBundle\Repository\StockRepository
     */
    public function getStockRepo()
    {
        return $this->getDoctrine()->getRepository('MedicalBundle:Stock');
    }

    /**
     * @return \Doctrine\Common\Persistence\ObjectRepository|\MedicalBundle\Repository\DispensaryRepository
     */
    public function getDispensaryRepo()
    {
        return $this->getDoctrine()->getRepository('MedicalBundle:Dispensary');
    }
}
