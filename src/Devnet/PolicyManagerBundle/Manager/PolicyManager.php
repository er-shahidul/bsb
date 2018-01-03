<?php

namespace Devnet\PolicyManagerBundle\Manager;

use Devnet\PolicyManagerBundle\Policy\PolicyGroupInterface;
use Devnet\PolicyManagerBundle\Repository\PolicyRepository;
use Doctrine\DBAL\Types\Type;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\FormFactory;
use Symfony\Component\Form\FormInterface;

class PolicyManager
{
    /** @var  PolicyGroupInterface[] */
    private $policyGroups = array();

    /**
     * @var PolicyRepository
     */
    private $repository;
    /**
     * @var FormFactory
     */
    private $formFactory;

    /**
     * PolicyManager constructor.
     * @param PolicyRepository | EntityRepository $policyRepository
     * @param FormFactory $formFactory
     * @param array $policyGroups
     */
    public function __construct(EntityRepository $policyRepository, FormFactory $formFactory, $policyGroups = array())
    {
        $this->repository = $policyRepository;
        $this->policyGroups = $policyGroups;
        $this->formFactory = $formFactory;
    }

    public function getPolicyJson($id, $default = array()) {
        $data = $this->repository->find($id);
        if(empty($data)) {
            return $default;
        }

        $data = json_decode($data->getValue(), true);

        if(json_last_error()) {
            return $default;
        }

        return array_merge_recursive($default, $data);
    }

    public function savePolicyJson($id, $data) {
        if(empty($data)) {
            return null;
        }

        $data = json_encode($data);

        if(json_last_error()) {
            return null;
        }

        return $this->repository->save($id, $data);
    }

    public function getPolicyValue($id, $type = null) {
        $value = $this->repository->getPolicyValue($id);

        if($type === null) {
            return $value;
        }

        return $this->typeCast($value, $type);
    }

    public function setPolicy($key, $data) {
        return $this->repository->save($key, $data);
    }

    public function getGroupPolicy($group)
    {
        $valuesByGroupKey = $this->repository->getValuesByGroupKey($group);

        return $valuesByGroupKey;
    }

    public function getPolicy($key)
    {
        return $this->repository->getPolicyValue($key);
    }

    /**
     * @param $group
     * @param null $data
     * @return FormInterface
     */
    public function getGroupPolicyForm($group, $data = null)
    {
        if ($data === null) {
            $data = $this->getGroupPolicy($group);
        }
        return $this->policyGroups[$group]->getForm($this->formFactory, $data);
    }

    /**
     * @param $group
     * @return string
     */
    public function getGroupPolicyLabel($group)
    {
        return $this->policyGroups[$group]::getLabel();
    }

    public function getGroupPolicyForms()
    {
        $return = array();

        foreach ($this->policyGroups as $groupKey => $policyGroup) {
            $return[] = array(
                'key' => $groupKey,
                'label' => $policyGroup::getLabel(),
                'form' => $this->createFormView($policyGroup, $groupKey)
            );
        }

        return $return;
    }

    public function saveGroupData($key, $data = array()) {
        $this->repository->saveMultiple($key, $data);
    }

    /**
     * @param $key
     * @return PolicyGroupInterface
     */
    public function getPolicyGroup($key)
    {
        return $this->policyGroups[$key];
    }

    /**
     * @return PolicyGroupInterface[]
     */
    public function getPolicyGroups()
    {
        return $this->policyGroups;
    }

    /**
     * @param $policyGroup
     * @param $groupKey
     * @return mixed
     */
    protected function createFormView(PolicyGroupInterface $policyGroup, $groupKey)
    {
        return $policyGroup
            ->getForm($this->formFactory, $this->getGroupPolicy($groupKey))
            ->createView();
    }

    /**
     * @param $value
     * @param $type
     * @return mixed
     */
    protected function typeCast($value, $type)
    {
        if(empty($value)) {
            return null;
        }

        switch ($type) {
            case Type::DATE:
            case Type::DATETIME:
                return new \DateTime($value);
            case Type::BOOLEAN:
                return boolval($value);
            case Type::INTEGER:
                return (int) $value;
            default:
                return $value;
        }
    }
}