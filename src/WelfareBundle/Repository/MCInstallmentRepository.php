<?php

namespace WelfareBundle\Repository;

use PersonnelBundle\Entity\ExServiceman;
use WelfareBundle\Entity\BSCRApplication;
use WelfareBundle\Entity\MicroCreditApplication;

class MCInstallmentRepository extends WelfareApplicationRepository
{
    /**
     * @param $serviceId
     * @return mixed
     */
    public function hasInstallment($serviceId)
    {
        $qb = $this->createQueryBuilder('b');
        $qb->join('b.application', 'm');
        $qb->join('m.serviceMan', 's');
        $qb->where('s.identityNumber = :identityNumber')->setParameter('identityNumber', $serviceId);
        $qb->setMaxResults(1);
        return $qb->getQuery()->getOneOrNullResult();
    }

    public function getLoanReceiver($exServiceman)
    {
        $qb = $this->createQueryBuilder('b');
        $qb->join('b.application', 'm');
        $qb->where('m.serviceMan = :serviceMan')->setParameter('serviceMan', $exServiceman);
        return $qb->getQuery()->getResult();
    }

    public function paymentInstallment(MicroCreditApplication $application)
    {
        $qb = $this->createQueryBuilder('b');
        $qb->where('b.application = :application')->setParameter('application', $application);
        $qb->andWhere('b.receivedAmount = 0');
        $qb->setMaxResults(1);
        $qb->orderBy('b.id', 'ASC');
        return $qb->getQuery()->getOneOrNullResult();
    }


}
