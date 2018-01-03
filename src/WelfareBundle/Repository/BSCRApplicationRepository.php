<?php

namespace WelfareBundle\Repository;

use PersonnelBundle\Entity\ExServiceman;
use WelfareBundle\Entity\BSCRApplication;

class BSCRApplicationRepository extends WelfareApplicationRepository
{
    public function getLastApplicationOfServiceMan($exServiceman)
    {
        $qb = $this->createQueryBuilder('w');
        $qb->where('w.serviceMan = :serviceMan')->setParameter('serviceMan', $exServiceman);
        $qb->andWhere('w.status != :status')->setParameter('status', 'rejected');
        $qb->orderBy('w.id', 'desc');
        $qb->setMaxResults(1);
        return $qb->getQuery()->getOneOrNullResult();
    }

}
