<?php

namespace BudgetBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="BudgetBundle\Repository\FundRequestRepository")
 */
class FundRequest extends BaseBudget
{
}