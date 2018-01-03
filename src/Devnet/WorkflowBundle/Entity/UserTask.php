<?php

namespace Devnet\WorkflowBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use UserBundle\Entity\User;

/**
 * @ORM\Entity(repositoryClass="Devnet\WorkflowBundle\Repository\UserTaskRepository")
 * @ORM\Table(name="user_task_queue")
 */
class UserTask extends TaskQueue
{
    /**
     * @var User
     *
     * @ORM\ManyToOne(targetEntity="UserBundle\Entity\User")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     */
    protected $user;

    /**
     * UserTask constructor.
     * @param User $user
     * @param string $refId
     * @param string $moduleId
     * @param string $entity
     * @param null $office
     */
    public function __construct(User $user = null, $refId = "", $moduleId = "", $entity = "", $office = null)
    {
        parent::__construct($refId, $moduleId, $entity, $office);
        $this->user = $user;
    }


    /**
     * @return User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @param User $user
     */
    public function setUser($user)
    {
        $this->user = $user;
    }
}

