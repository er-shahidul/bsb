<?php

namespace AccountBundle\Form\Type;

use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class FundTypeType extends AbstractType
{
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'class' => 'AccountBundle:FundType',
            'choice_label' => 'name',
            'query_builder' => function(EntityRepository $repo) {
                return $repo->createQueryBuilder('b');
            },
            'attr' => ['class' => 'bs-select', 'data-live-search' => 'true', 'data-size' => '8']
        ));
    }

    public function getParent()
    {
        return EntityType::class;
    }
}