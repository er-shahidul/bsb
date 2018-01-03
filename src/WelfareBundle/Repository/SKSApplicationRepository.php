<?php

namespace WelfareBundle\Repository;

use AppBundle\Repository\BaseRepository;
use WelfareBundle\Entity\SKSApplicationType;

class SKSApplicationRepository extends BaseRepository
{
    public function getLastApplicationOfServiceMan(SKSApplicationType $type, $exServiceman)
    {
        $qb = $this->createQueryBuilder('s');
        $qb->where('s.serviceMan = :serviceMan')->setParameter('serviceMan', $exServiceman);
        $qb->andWhere('s.type = :type')->setParameter('type', $type);
        $qb->andWhere('s.status != :status')->setParameter('status', 'rejected');
        $qb->orderBy('s.id', 'desc');
        $qb->setMaxResults(1);
        return $qb->getQuery()->getOneOrNullResult();
    }
}
