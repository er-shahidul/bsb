<?php

namespace Devnet\PolicyManagerBundle\Repository;

use Devnet\PolicyManagerBundle\Entity\Policy;
use Doctrine\ORM\EntityRepository;

class PolicyRepository extends EntityRepository
{
    public function loadAllByGroup($groupKey)
    {
        $qb = $this->createQueryBuilder('p');

        $policies = $qb
            ->where($qb->expr()->like('p.id', ':group_key'))
            ->setParameter('group_key', $groupKey . '.%')
            ->getQuery()
            ->getResult();

        if (!$policies) {
            return NULL;
        }

        $keyLength = strlen($groupKey) + 1;
        $return = array();

        /** @var  $policy Policy */
        foreach ($policies as $policy) {
            $return[substr($policy->getId(), $keyLength)] = $policy;
        }

        return $return;
    }

    public function getValuesByGroupKey($policyGroup)
    {
        return array_map(array($this, 'getValue'), (array)$this->loadAllByGroup($policyGroup));
    }

    protected function getValue(Policy $policy)
    {
        return $policy->getValue();
    }

    public function getPolicyValue($key)
    {
        $policy = $this->find($key);

        if($policy == null) {
            return null;
        }

        return $policy->getValue();
    }

    public function save($key, $value, $locked = false, $force = false, $flush = true)
    {
        $policy = $this->find($key);

        if (!$policy) {
            $policy = new Policy($key);
        } elseif ($policy->isLocked() && !$force) {
            return $this;
        }

        $policy->setValue($value);
        $policy->setLocked((bool)$locked);

        $this->_em->persist($policy);

        if ($flush) {
            $this->_em->flush($policy);
        }

        return $policy;
    }

    function saveMultiple($baseKey, $values = array())
    {
        foreach ($values as $key => $value) {
            $this->save($baseKey . ".{$key}", $value, false, false, false);
        }

        $this->_em->flush();
    }
}
