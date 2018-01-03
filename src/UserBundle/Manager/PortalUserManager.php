<?php

namespace UserBundle\Manager;

use AppBundle\Entity\Employee;
use AppBundle\Repository\EmployeeRepository;
use Doctrine\ORM\EntityManager;
use Symfony\Component\Validator\Constraints\DateTime;

class PortalUserManager
{
    /** @var EntityManager */
    protected $em;

    /** @var EmployeeRepository */
    protected $userRepo;

    public function __construct(EntityManager $entityManager)
    {
        $this->em = $entityManager;
        $this->userRepo = $entityManager->getRepository('AppBundle:Employee');
    }

    public function generateAuthKey(Employee $employee)
    {
        $key = sha1($employee->getServiceId() . $employee->getPin() . $employee->getId());
        $employee->setAuthKey($key);
        $employee->setAuthKeyExpire(new DateTime('30+ min'));
        $this->em->persist($employee);
        $this->em->flush();
    }

    public function getUserByServiceId($serviceId)
    {
        return $this->userRepo->findOneBy(array('serviceId' => $serviceId));
    }

    public function getUserById($id)
    {
        return $this->userRepo->find($id);
    }

    public function getUserByAuthKey($authKey)
    {
        return $this->userRepo->findOneBy(array('authKey' => $authKey));
    }
}