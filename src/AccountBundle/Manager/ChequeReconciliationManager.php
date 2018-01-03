<?php

namespace AccountBundle\Manager;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class ChequeReconciliationManager
{
    /**
     * @var EntityManagerInterface
     */
    protected $em;

    protected $office;

    public function __construct(EntityManagerInterface $entityManager, TokenStorageInterface $tokenStorage)
    {
        $this->em = $entityManager;
        $this->office = $tokenStorage->getToken()->getUser()->getOffice();
    }
}