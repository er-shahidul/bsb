<?php

namespace WelfareBundle\Manager;

use AppBundle\Entity\Office;
use BoardMeetingBundle\Entity\BoardMeeting;
use Devnet\PolicyManagerBundle\Manager\PolicyManager;
use Doctrine\DBAL\Types\Type;
use Doctrine\ORM\EntityManagerInterface;
use PersonnelBundle\Entity\ExServiceman;
use WelfareBundle\Entity\BSCRApplication;
use WelfareBundle\Entity\MCDefaulter;
use WelfareBundle\Entity\MCDefaulterRegister;
use WelfareBundle\Entity\WelfareApplication;

class MCPaymentManager
{
    /**
     * @var EntityManagerInterface
     */
    protected $em;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->em = $entityManager;
    }

    public function defaulterRequest($office, $ids) {

        $defaulterRegister = new MCDefaulterRegister();
        $defaulterRegister->setOffice($office);
        $defaulterRegister->setStatus('draft');
        $this->em->persist($defaulterRegister);

        foreach ($ids as $id) {

            $application = $this->em->getRepository('WelfareBundle:MicroCreditApplication')->find($id);

            $defaulter = new MCDefaulter();
            $defaulter->setDefaulterRegister($defaulterRegister);
            $defaulter->setApplication($application);
            $this->em->persist($defaulter);
        }
        $this->em->flush();
    }
}