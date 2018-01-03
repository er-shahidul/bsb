<?php

namespace AppBundle\Repository;

class OfficeRepository extends BaseRepository
{
    public function getWhichBudgetRequestIsNotAwaitingForBudgetCompilation($year)
    {
        $connection = $this->_em->getConnection();
        $sql = "SELECT o.id, o.name
                FROM offices o
                LEFT JOIN budget b
                ON(b.office_id = o.id and b.financial_year_id = ? and b.status = ?)
                WHERE b.id is NULL";
        $stmt = $connection->prepare($sql);
        $stmt->bindValue(1, $year);
        $stmt->bindValue(2, 'wait_for_budget_compilation');
        $stmt->execute();

        return $stmt->fetchAll();
    }

    public function getHQOffice()
    {
        $officeType = $this->_em->getRepository('AppBundle:OfficeType')->findOneBy(['name' => 'HQ']);

        return $this->findOneBy(['officeType' => $officeType]);
    }

    public function getDASBOffices()
    {
        $officeType = $this->_em->getRepository('AppBundle:OfficeType')->findOneBy(['name' => 'HQ']);
        $qb = $this->createQueryBuilder('o');
        $qb->where('o.officeType <> :officeType')->setParameter('officeType', $officeType);
        return $qb->getQuery()->getResult();
    }

    public function officeAutoComplete()
    {
        $query = $this->createQueryBuilder('o');
        $result = $query->getQuery()->getResult();
        foreach ($result as $value){
            $str[] = $value->getName();
        }

        /** @var $str */
        return $str;
    }
}