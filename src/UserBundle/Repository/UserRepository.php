<?php

namespace UserBundle\Repository;

use AppBundle\Repository\BaseRepository;
use UserBundle\Entity\User;

/**
 * UserRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class UserRepository extends BaseRepository
{
    public function getAll()
    {
        return $this->findAll();
    }

    public function create($data)
    {
        $this->_em->persist($data);
        $this->_em->flush();
    }

    public function delete($data)
    {
        $this->_em->remove($data);
        $this->_em->flush();
    }

    public function update($data)
    {
        $this->_em->persist($data);
        $this->_em->flush();
        return $this->_em;
    }

    /**
     * @param int $limit
     *
     * @return User[]
     */
    public function getRandomUser($limit = 3)
    {
        return $this->getRandom($limit);
    }

    public function getUsersWithoutAdministrator(User $ignoreUser = NULL)
    {
        $qb = $this->createQueryBuilder('u');

        $qb
            ->join('u.groups', 'groups')
            ->where($qb->expr()->neq('groups.name', ':superAdminGroup'))
            ->andWhere($qb->expr()->eq('u.enabled', TRUE))
        ;

        if($ignoreUser !== NULL) {
            $qb
                ->andWhere($qb->expr()->neq('u', ':user'))
                ->setParameter('user', $ignoreUser)
            ;
        }

        $qb->setParameter('superAdminGroup', 'Super Administrator');

        return $qb;
    }
}