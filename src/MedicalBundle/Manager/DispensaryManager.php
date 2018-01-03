<?php

namespace MedicalBundle\Manager;

use MedicalBundle\Entity\Dispensary;
use Symfony\Component\DependencyInjection\ContainerInterface;

class DispensaryManager
{
    protected $container;
    protected $office;
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function setOffice($office)
    {
        $this->office = $office;
    }

    public function getDispensaryAsArrayForRequisition(Dispensary $defaultDispensary = null)
    {
        $workflow = $this->container->get('workflow.medical_requisition_workflow');
        $places = $workflow->getDefinition()->getPlaces();
        unset($places['approved']);

        $dispensaryRepo = $this->container->get('doctrine.orm.entity_manager')->getRepository('MedicalBundle:Dispensary');

        $dispensaries = $dispensaryRepo->getAvailableDispensaryForRequisition($this->office);

        $data = [];
        if ($defaultDispensary) {
            $data[$defaultDispensary->getName()] = $defaultDispensary->getId();
        }
        /** @var Dispensary $dispensary */
        foreach ($dispensaries as $dispensary) {
            $data[$dispensary->getName()] = $dispensary->getId();
        }

        return $data;
    }
}