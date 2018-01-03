<?php

namespace BudgetBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Budget
 * @ORM\Entity(repositoryClass="BudgetBundle\Repository\BudgetRepository")
 */
class Budget extends BaseBudget{}