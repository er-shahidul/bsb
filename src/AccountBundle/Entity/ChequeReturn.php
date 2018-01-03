<?php

namespace AccountBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ChequeReconciliation
 * @ORM\Entity(repositoryClass="AccountBundle\Repository\ReconciliationRepository")
 */
class ChequeReturn extends Reconciliation
{

}