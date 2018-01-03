<?php

namespace WelfareBundle\Entity;

use AppBundle\Entity\AttachmentsTrait;
use BoardMeetingBundle\Core\BoardMeetingRelationTrait;
use Devnet\WorkflowBundle\Entity\BaseWorkflowEntity;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use PersonnelBundle\Entity\ExFamilyInformation;
use PersonnelBundle\Entity\ExServiceman;

/**
 * WelfareApplication
 *
 * @ORM\Table(name="welfare_applications")
 * @ORM\Entity(repositoryClass="WelfareBundle\Repository\WelfareApplicationRepository")
 * @ORM\InheritanceType("SINGLE_TABLE")
 * @ORM\DiscriminatorColumn(name="welfare_type", type="string")
 * @ORM\DiscriminatorMap({"base" = "WelfareApplication", "bscr" = "BSCRApplication", "rcel" = "RCELApplication", "micro-credit" = "MicroCreditApplication"})
 */
class WelfareApplication extends BaseWelfareApplication
{
    use BoardMeetingRelationTrait;

    /**
     * @var array
     *
     * @ORM\Column(name="member_comments", type="json_array", nullable=true)
     */
    private $memberComments = array();

    public function __construct()
    {
        $this->attachments = new ArrayCollection();
    }

    /**
     * @return array
     */
    public function getMemberComments()
    {
        return $this->memberComments;
    }

    /**
     * @param array $memberComments
     *
     * @return WelfareApplication
     */
    public function setMemberComments($memberComments)
    {
        $this->memberComments = $memberComments;

        return $this;
    }

    public function getType()
    {
           return 'base';
    }
}

