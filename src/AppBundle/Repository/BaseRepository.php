<?php

namespace AppBundle\Repository;

use Doctrine\ORM\EntityRepository;

class BaseRepository extends EntityRepository
{
    public function update($entity)
    {
        $this->save($entity);
    }

    public function save($entity)
    {
        $this->_em->persist($entity);
        $this->_em->flush();
    }


    /**
     * @param int    $limit
     * @param string $columnName
     * @param string $fieldName
     *
     * @return array
     */
    public function getRandom($limit = 7, $columnName = 'id', $fieldName = 'id')
    {
        $table = $this->getClassMetadata()
            ->getTableName();
        $connection = $this->_em->getConnection();
        $sql = "SELECT o.{$columnName}
                FROM {$table} o
                ORDER BY RAND() LIMIT 0, {$limit}";
        $stmt = $connection->prepare($sql);
        $stmt->execute();

        $a = $stmt->fetchAll();
        $items = array_map(function ($item) {
            return $item['id'];
        }, $a);

        $qb = $this
            ->createQueryBuilder('e');

        return $qb
            ->where($qb->expr()->in("e", ':items'))
            ->setParameter('items', $items)
            ->getQuery()
            ->getResult();
    }
}