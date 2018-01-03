<?php

namespace AppBundle\Controller;


use Doctrine\ORM\EntityRepository;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class UniqueController extends Controller
{
    /**
     * @Route("/check-unique-entry", name="custom_unique_controller")
     */
    public function indexAction(Request $request)
    {
        $data = $request->request->all();
        foreach ($data['data'] as $value) {
            // If field(s) has an empty value and it should be ignored
            if ((bool)$data['ignoreNull'] && ('' === $value || is_null($value))) {
                // Just return a positive result
                return new JsonResponse(true);
            }
        }

        /** @var EntityRepository $repo */
        $repo = $this
            ->get('doctrine')
            ->getRepository($data['entityName']);

        if (empty($data['id'])) { //New Request / Default behaviour
            $entity = $repo
                ->{$data['repositoryMethod']}($data['data']);
        } else { //Update check
            $em = $this->getDoctrine()->getManager();
            $meta = $em->getClassMetadata($data['entityName']);
            $identifier = $meta->getSingleIdentifierFieldName();

            $qb = $repo->createQueryBuilder('e');
            $qb
                ->where(
                    $qb->expr()->neq('e.' . $identifier, ':id_value')
                );

            foreach ($data['data'] as $key => $value) {
                $qb->andWhere(
                    $qb->expr()->eq('e.' . $key, ':'.$key)
                )->setParameter($key, $value);
            }

            $qb->setParameter('id_value', $data['id']);
            $entity = $qb->getQuery()->getOneOrNullResult();
        }

        return new JsonResponse(empty($entity));
    }
}