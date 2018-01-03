<?php

namespace Devnet\WorkflowBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use UserBundle\Entity\Group;

/**
 * @ORM\Entity(repositoryClass="Devnet\WorkflowBundle\Repository\GroupTaskRepository")
 * @ORM\Table(name="group_task_queue")
 */
class GroupTask extends TaskQueue
{
    /**
     * @var Group
     *
     * @ORM\ManyToOne(targetEntity="UserBundle\Entity\Group")
     * @ORM\JoinColumn(name="group_id", referencedColumnName="id")
     */
    protected $group;

    /**
     * GroupTask constructor.
     * @param Group $group
     * @param string $refId
     * @param string $moduleId
     * @param $entity
     */
    public function __construct(Group $group = null, $refId = "", $moduleId = "", $entity = "", $office = null)
    {
        parent::__construct($refId, $moduleId, $entity, $office);
        $this->group = $group;
    }


    /**
     * @return mixed
     */
    public function getGroup()
    {
        return $this->group;
    }

    /**
     * @param mixed $group
     */
    public function setGroup($group)
    {
        $this->group = $group;
    }
}

