<?php

namespace MedicalBundle\Repository;

use AppBundle\Entity\Office;
use AppBundle\Repository\BaseRepository;
use MedicalBundle\Entity\Dispensary;
use MedicalBundle\Entity\Requisition;
use Symfony\Component\Workflow\Registry;

/**
 * DispensaryRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class DispensaryRepository extends BaseRepository
{
    public function getQueryBuilderForDespensary($params)
    {
        $qb = $this->createQueryBuilder('dispensary');

        if (isset($params['office']) && $params['office'] instanceof Office) {
            $qb->andWhere($qb->expr()->eq('dispensary.office', ':office'));
            $qb->setParameter('office', $params['office']);
        }
        if (isset($params['enable']) && is_bool($params['enable'])) {
            $qb->andWhere($qb->expr()->eq('dispensary.enable', ':enable'));
            $qb->setParameter('enable', $params['enable']);
        }

        return $qb;
    }

    public function getDespensariesForOffice(Office $office)
    {
        return $this->getQueryBuilderForDespensary([
            'office' => $office,
            'enable' => true,
        ])->getQuery()->getResult();
    }

    public function getAvailableDispensaryForRequisition(Office $office)
    {
        $requisitionRepo = $this->_em->getRepository('MedicalBundle:Requisition');
        $data = [];
        /** @var Dispensary $dispensary */
        foreach ($this->getQueryBuilderForDespensary(['office' => $office])->getQuery()->getResult() as $dispensary){
            $req = $requisitionRepo->findOneBy(['dispensary' => $dispensary], ['createdAt' => 'desc']);
            if (!$req || $req->getStatus() == 'approved') {
                $data[] = $dispensary;
            }
        }

        return $data;
    }
}
