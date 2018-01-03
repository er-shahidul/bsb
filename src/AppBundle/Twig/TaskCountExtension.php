<?php

namespace AppBundle\Twig;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class TaskCountExtension extends \Twig_Extension
{
    /** @var  EntityManagerInterface */
    protected $em;

    protected $userId;

    public function __construct(EntityManagerInterface $entityManager, TokenStorageInterface $tokenStorage)
    {
        $this->em = $entityManager;

        if ($tokenStorage->getToken() && is_object($user = $tokenStorage->getToken()->getUser())) {
            $this->userId = $tokenStorage->getToken()->getUser()->getId();
        }
    }

    public function getFunctions()
    {
        return [
            new \Twig_SimpleFunction('userTaskCount', [$this, 'getTaskCount'])
        ];
    }

    function getTaskCount() {
        return $this->em->getRepository('DevnetWorkflowBundle:UserTask')->countUserTask($this->userId);
    }

    public function getName()
    {
        return 'AppBundle:TaskCountExtension';
    }
}
