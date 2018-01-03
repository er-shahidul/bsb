<?php

namespace WelfareBundle\Repository;

use BoardMeetingBundle\Entity\BoardMeeting;
use Doctrine\DBAL\Types\BooleanType;
use PersonnelBundle\Entity\ExServiceman;
use WelfareBundle\Entity\BSCRApplication;

class MicroCreditApplicationRepository extends WelfareApplicationRepository
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

    public function defaulterPayments($office) {

        $qb = $this->createQueryBuilder('m');
        $qb->join('m.microCreditDetail', 'd');
        $qb->where('d.loanCompleted != :completed')->setParameter('completed', true);

        if ($office && strtolower($office->getOfficeType()->getName()) == 'dasb') {
            $qb->andWhere('m.office = :office')->setParameter('office', $office);
        }
        $applications = $qb->getQuery()->getResult();

        foreach ($applications as $application) {
            $totalPayable = $this->totalPayable($application);
            $application->getMicroCreditDetail()->setTotalPayable($totalPayable);
        }

        return $applications;
    }


}
