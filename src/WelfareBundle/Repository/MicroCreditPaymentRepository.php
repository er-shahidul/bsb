<?php

namespace WelfareBundle\Repository;

use AppBundle\Repository\BaseRepository;
use BoardMeetingBundle\Entity\BoardMeeting;
use WelfareBundle\Entity\MicroCreditApplication;

class MicroCreditPaymentRepository extends BaseRepository
{
    /**
     * @param MicroCreditApplication $application
     * @return array
     */
    public function paymentHistory(MicroCreditApplication $application) {
        return $this->findBy(array('application' => $application, 'status' => 'approved'), array('createdAt' => 'asc'));
    }

    public function getDefaultersByMeeting(BoardMeeting $meeting, $today=null) {

        if (!$today) {
            $today =  new \DateTime();
        }

        $qbr = $this->getEntityManager()->getRepository('WelfareBundle:MCDefaulterRegister')->createQueryBuilder('r');
        $qbr->where("r.status != :status")->setParameter('status', 'approved');
        $rows = $qbr->getQuery()->getResult();

        $defaulters = [];
        foreach ($rows as $row) {
            $defaulters = array_merge($defaulters, $row->getDefaulterRemarks());
        }

        $subQuery = $this->getEntityManager()->createQueryBuilder()
            ->select('SUM(i.installmentAmount)')
            ->from('WelfareBundle:MCInstallment', 'i')
            ->join('i.application', 'ma')
            ->where('ma.id = a.id')
            ->andWhere('i.dueDate < :today')
            ->getDQL();

        $qb = $this->getEntityManager()->createQueryBuilder()
            ->select('a')
            ->addSelect('d.totalPaid AS totalPaid')
            ->addSelect(sprintf('(%s) AS totalPayable', $subQuery))
            ->from('WelfareBundle:MicroCreditApplication', 'a')
            ->join('a.microCreditDetail', 'd')
            ->setParameter('today', $today);
        $qb->andWhere('a.meeting = :meeting')->setParameter('meeting', $meeting);
        $qb->andWhere('a.id NOT IN (:defaulters)')->setParameter('defaulters', $defaulters);
        $qb->having($qb->expr()->lt("totalPaid", "totalPayable"));

        return $qb->getQuery()->getResult();
    }

    public function getDefaultersByIds($applicationIds, $today=null) {

        if (!$today) {
            $today =  new \DateTime();
        }

        $subQuery = $this->getEntityManager()->createQueryBuilder()
            ->select('SUM(i.installmentAmount)')
            ->from('WelfareBundle:MCInstallment', 'i')
            ->join('i.application', 'ma')
            ->where('ma.id = a.id')
            ->andWhere('i.dueDate < :today')
            ->getDQL();

        $qb = $this->getEntityManager()->createQueryBuilder()
            ->select('a')
            ->addSelect('d.totalPaid AS totalPaid')
            ->addSelect(sprintf('(%s) AS totalPayable', $subQuery))
            ->from('WelfareBundle:MicroCreditApplication', 'a')
            ->join('a.microCreditDetail', 'd')
            ->setParameter('today', $today);
            $qb->andWhere('a.id IN (:ids)')->setParameter('ids', array_values($applicationIds));

        return $qb->getQuery()->getResult();
    }

    public function totalPayable($application) {
        $today =  new \DateTime('2018-08-10');//todo

        $qb = $this->getEntityManager()->getRepository('WelfareBundle:MCInstallment')->createQueryBuilder('i');
        $qb->select('SUM(i.installmentAmount)');
        $qb->where('i.application = :application')->setParameter('application', $application);
        $qb->andWhere('i.dueDate < :today')->setParameter('today', $today);
        return $qb->getQuery()->getSingleScalarResult();
    }
}
