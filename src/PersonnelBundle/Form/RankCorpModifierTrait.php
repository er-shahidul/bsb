<?php

namespace PersonnelBundle\Form;

use Doctrine\ORM\EntityRepository;
use PersonnelBundle\Entity\Corp;
use PersonnelBundle\Entity\Rank;
use PersonnelBundle\Entity\Service;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\PropertyAccess\PropertyAccess;

trait RankCorpModifierTrait
{
    /**
     * @param FormBuilderInterface $builder
     */
    protected function modifyRankAndCorp(FormBuilderInterface $builder, $isServing = FALSE)
    {
        $addCorpAndRank = function (FormInterface $form, $service) {
            $choices = [];
            $childQueryBuilder = NULL;
            if (!empty($service)) {
                $choices = NULL;
                $childQueryBuilder = function (EntityRepository $er) use ($service) {
                    $qb = $er->createQueryBuilder('child');

                    if ($service instanceof Service) {
                        $qb = $qb->where('child.service = :service')
                            ->setParameter('service', $service);
                    } else {
                        $qb = $qb
                            ->innerJoin('child.service', 'service')
                            ->where('service.id = :id')
                            ->setParameter('id', $service);
                    }

                    return $qb;
                };
            }

            self::addFields($form, $choices, $childQueryBuilder);
        };

        $options = [
            'class'       => Service::class,
            'label'       => 'বাহিনী',
            'required'    => TRUE,
            'attr'        => [
                'class' => 'personnel-service-option'
            ],
            'placeholder' => 'বাহিনী'
        ];

        if($isServing) {
            $options['query_builder'] = function (EntityRepository $er) {
                $qb = $er->createQueryBuilder('s');
                return $qb
                    ->where($qb->expr()->neq('s.id', ':type'))
                    ->setParameter('type', 'Ex British')
                    ;
            };
        }

        $builder
            ->add('service', EntityType::class, $options);

        self::addFields($builder);

        $builder
            ->addEventListener(FormEvents::POST_SET_DATA, function (FormEvent $event) use ($addCorpAndRank) {
                $data = $event->getData();
                $form = $event->getForm();

                if (NULL === $data) {
                    return;
                }

                $accessor = PropertyAccess::createPropertyAccessor();
                $service = $accessor->getValue($data, 'service');
                $addCorpAndRank($form, $service);

            });

        $builder
            ->addEventListener(FormEvents::PRE_SUBMIT, function (FormEvent $event) use ($addCorpAndRank) {
                $form = $event->getForm();
                $data = $event->getData();

                if (!empty($data) && array_key_exists('service', $data)) {
                    $addCorpAndRank($form, $data['service']);
                }
            });
    }

    /**
     * @param FormInterface|FormBuilderInterface      $builder
     * @param array $choices
     * @param null  $queryBuilder
     */
    public static function addFields($builder, $choices = [], $queryBuilder = NULL)
    {

        $builder->add('rank', EntityType::class, [
            'required'    => TRUE,
            'placeholder' => 'পদবি',
            'label'       => 'পদবি',
            'class'       => Rank::class,
            'query_builder' => $queryBuilder,
            'attr'        => [
                'class' => 'personnel-rank-option'
            ],
            'choices'     => $choices
        ])
            ->add('corp', EntityType::class, [
                'required'    => FALSE,
                'placeholder' => 'কোর / রেজিমেন্ট',
                'class'       => Corp::class,
                'attr'        => [
                    'class' => 'personnel-corp-option'
                ],
                'choices'     => $choices,
                'query_builder' => $queryBuilder,
                'label'       => 'কোর / রেজিমেন্ট'
            ]);
    }
}