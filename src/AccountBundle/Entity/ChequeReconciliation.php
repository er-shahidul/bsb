<?php

namespace AccountBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ChequeReconciliation
 * @ORM\Entity(repositoryClass="AccountBundle\Repository\ReconciliationRepository")
 */
class ChequeReconciliation extends Reconciliation
{

}