<?php

namespace UserBundle\Controller;

use Symfony\Component\Routing\Annotation\Route;

class UserTaskQueueController extends UserBaseController
{
    /**
     * @Route("/my-tasks", name="my-tasks")
     */
    public function indexAction()
    {
        return $this->render('UserBundle:UserTaskQueue:index.html.twig', [
            'tasks' => $this
                ->getDoctrine()
                ->getRepository('DevnetWorkflowBundle:UserTask')
                ->findBy(['user' => $this->getUser()]),
            'moduleNameMap' => $this->getModuleNameMapping()
        ]);
    }

    protected function getModuleNameMapping()
    {
        return [
            'office_budget' => 'Budget',
            'budget_compilation' => 'Budget Compilation',
            'budget_allocation' => 'Budget Alloc',
        ];
    }

}
