<?php

namespace WelfareBundle\Manager;

use Doctrine\ORM\EntityManagerInterface;
use PersonnelBundle\Entity\ExServiceman;

class BaseWelfareManager
{
    /**
     * @var EntityManagerInterface
     */
    protected $em;

    /**
     * WelfareManager constructor.
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->em = $entityManager;
    }

    /**
     * @param ExServiceman $exServiceMan
     * @return mixed
     */
    public function receivedFunds(ExServiceman $exServiceMan, $lastRecord = false)
    {
        $qb = $this->em->getRepository('PersonnelBundle:FundReceived')->createQueryBuilder('f');
        $qb->where('f.serviceman = :serviceMan')->setParameter('serviceMan', $exServiceMan);
        $qb->orderBy('f.id', 'desc');
        return $qb->getQuery()->getResult();
    }

    /**
     * @param ExServiceman $exServiceMan
     * @return mixed
     */
    public function lastReceivedFund($fundType, ExServiceman $exServiceMan)
    {
        $fundDate = '';
        $lastFund = null;

        $receivedFunds = $exServiceMan->getReceivedFunds();

        foreach ($receivedFunds as $key => $fund) {
            if ($fund->getFundType()->getId() == $fundType) {
                if ($fundDate < $fund->getDate()->format('Y-m-d')) {
                    $fundDate = $fund->getDate()->format('Y-m-d');
                    $lastFund = $fund;
                }
            }
        }
        return $lastFund;
    }

    public function getServiceManByServiceId($serviceId) {
        /** @var ExServiceman $exServiceMan */
        return $this->em->getRepository('PersonnelBundle:ExServiceman')->findOneBy(['identityNumber' => $serviceId]);
    }
}