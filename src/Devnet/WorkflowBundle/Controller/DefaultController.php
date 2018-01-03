<?php

namespace Devnet\WorkflowBundle\Controller;

use Devnet\WorkflowBundle\Core\WorkflowStepRemark;
use Devnet\WorkflowBundle\Entity\BaseWorkflowEntity;
use Devnet\WorkflowBundle\Entity\UserTask;
use Devnet\WorkflowBundle\Event\GetResponseWorkflowEvent;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\BufferedOutput;
use Symfony\Component\EventDispatcher\GenericEvent;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Process\Process;

class DefaultController extends Controller
{
    public function applyAction($name, $transition, $ref, Request $request)
    {
        $entityClass = $request->request->get('entity');

        $entityRepository = $this->getDoctrine()->getRepository($entityClass);

        /** @var BaseWorkflowEntity $entity */
        $entity = $entityRepository->find($ref);

        $workflow = $this->getWorkflow()->get($entity, $name);

        if (!$workflow->can($entity, $transition)) {
            return new JsonResponse(array(
                'success' => false,
                'msg' => "You don't have enough permission to do this action!",
            ));
        }

        $remark = $request->request->get('step_remark', "");

        $place = $this->getCurrentPlace($workflow, $entity);
        $entity->setStepRemark(new WorkflowStepRemark($place, trim($remark)));

        $this->get('event_dispatcher')->dispatch('workflow_step_remark_save', new GenericEvent($entity, [
            'place' => $place,
            'remarkInfo' => [
                'name' => $this->getUser()->getProfile()->getName(),
                'rank' => '',
                'remark' => trim($remark),
                'date' => $entity->getUpdatedAt()->format('Y-m-d'),
                'office' => $this->getUser()->getOffice()->getName() .' '. $this->getUser()->getOffice()->getOfficeType()->getName()
            ]
        ]));

        $workflow->apply($entity, $transition);

        $this->getDoctrine()->getManager()->flush();

        return new JsonResponse(array(
            'success' => true,
            'msg' => 'Workflow action performed successfully!'
        ));
    }

    public function myWorkflowAction(UserTask $userTask)
    {
        $entityRepository = $this->getDoctrine()->getRepository($userTask->getEntity());

        /** @var BaseWorkflowEntity $entity */
        $entity = $entityRepository->find($userTask->getRefId());

        if ($entity == null) {
            throw $this->createNotFoundException();
        }

        $event = new GetResponseWorkflowEvent($entity);

        $this->get('event_dispatcher')->dispatch('workflow.view.event', $event);

        if ($event->getResponse() == null && $event->getResponseBuilder() == null) {
            throw $this->createNotFoundException();
        }

        if($event->getResponse() == null) {
            $data = $event->getResponseBuilder()->getData();
            $data['task'] = $userTask;
            return $this->render($event->getResponseBuilder()->getView(), $data);
        }

        return $event->getResponse();

    }

    public function statusImageAction($name, $marking, $id)
    {
        return $this->getImageResponse($name, $id, $marking);
    }

    public function myEntityStatusImageAction($entity, $id)
    {
        $entityClass = str_replace('/', '\\', $entity);

        $entityRepository = $this->getDoctrine()->getRepository($entityClass);

        /** @var BaseWorkflowEntity $entity */
        $entity = $entityRepository->find($id);

        if ($entity == NULL) {
            throw $this->createNotFoundException();
        }

        $workflow = $this->getWorkflow()->get($entity);

        $name = $workflow->getName();

        $marking = null;
        foreach ($workflow->getMarking($entity)->getPlaces() as $m => $v) {
            $marking = $m;
        }

        $id = $entity->getId();

        return $this->getImageResponse($name, $id, $marking);
    }

    public function myTaskStatusImageAction(UserTask $userTask)
    {
        $userTask->getEntity();

        $entityRepository = $this->getDoctrine()->getRepository($userTask->getEntity());
        /** @var BaseWorkflowEntity $entity */
        $entity = $entityRepository->find($userTask->getRefId());

        if ($entity == null) {
            throw $this->createNotFoundException();
        }

        $name = $userTask->getModuleId();
        $workflow = $this->getWorkflow()->get($entity, $name);
        $marking = null;
        foreach ($workflow->getMarking($entity)->getPlaces() as $m => $v) {
            $marking = $m;
        }

        $id = $entity->getId();

        return $this->getImageResponse($name, $id, $marking);
    }

    /**
     * @return \Doctrine\Bundle\DoctrineBundle\Registry|object
     */
    protected function getDoctrine()
    {
        return $this->container->get('doctrine');
    }


    protected function getWorkflow()
    {
        return $this->container->get('workflow.registry');
    }

    /**
     * @param $workflow
     * @param $entity
     * @return string
     */
    private function getCurrentPlace($workflow, $entity)
    {
        return implode(', ', array_keys($workflow->getMarking($entity)->getPlaces()));
    }

    /**
     * @param $name
     * @param $id
     * @param $marking
     *
     * @return Response
     */
    private function getImageResponse($name, $id, $marking)
    {
        $fileHash = md5("{$name}-{$id}");
        $dot = "uploads/workflow/" . $fileHash . ".dot";

        $application = new Application($this->get('kernel'));
        $application->setAutoExit(FALSE);

        $input = new ArrayInput([
            'command' => "basb:workflow:dump",
            'name'    => $name,
            'marking' => [$marking],
            '-vvv'    => ''
        ]);
        // You can use NullOutput() if you don't need the output
        $output = new BufferedOutput();
        $application->run($input, $output);

        // return the output, don't use if you used NullOutput()
        $dotContent = $output->fetch();

        file_put_contents($dot, $dotContent);


        $png = "uploads/workflow/" . $fileHash . ".png";

        $cmd = "dot -Tpng -o $png $dot";

        $process = new Process($cmd);
        $process->run();
        if (!$process->isSuccessful()) {
            throw new ProcessFailedException($process);
        }

        $response = new Response(file_get_contents($png), 200, [
            'Content-Type'        => 'image/png',
            'Content-Disposition' => 'inline; filename="' . $fileHash . '.png"'
        ]);
        $fs = new Filesystem();
        $fs->remove($dot);
        $fs->remove($png);

        return $response;
    }
}
