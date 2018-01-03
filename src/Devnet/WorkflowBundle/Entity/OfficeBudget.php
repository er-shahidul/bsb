<?php

namespace Devnet\WorkflowBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * OfficeBudget
 *
 * @ORM\Table(name="office_budgets")
 * @ORM\Entity(repositoryClass="Devnet\WorkflowBundle\Repository\OfficeBudgetRepository")
 */
class OfficeBudget
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="status", type="string", length=255, nullable=true)
     */
    private $status;

    /**
     * @var string
     *
     * @ORM\Column(name="lastComment", type="string", length=255, nullable=true)
     */
    private $lastComment;


    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set status
     *
     * @param string $status
     *
     * @return OfficeBudget
     */
    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Get status
     *
     * @return string
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Set lastComment
     *
     * @param string $lastComment
     *
     * @return OfficeBudget
     */
    public function setLastComment($lastComment)
    {
        $this->lastComment = $lastComment;

        return $this;
    }

    /**
     * Get lastComment
     *
     * @return string
     */
    public function getLastComment()
    {
        return $this->lastComment;
    }

    public static function getTransactionAccessRoles()
    {
        return [
            'forward_to_io' => 'DASB_CLERK',
            'forward_to_secretary' => 'IO',
            'send_to_basb' => 'SECRETARY',
            'send_back_to_io' => 'SECRETARY',
            'send_back_to_dasb_clark' => 'IO',
            'received_by_clark' => 'BASB_CLERK',
        ];
    }
}