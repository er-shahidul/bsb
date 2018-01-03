<?php

namespace BudgetBundle\Form\Type;

use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class BudgetIncomeHeadType extends AbstractType
{
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'class' => 'BudgetBundle:BudgetIncomeHead',
            'group_by' => 'parent',
            'choice_label' => 'label',
            'query_builder' => function(EntityRepository $repo) {
                return $repo->createQueryBuilder('b')->andWhere('b.parent IS NOT NULL');
            },
            'attr' => ['class' => 'bs-select', 'data-live-search' => 'true', 'data-size' => '8']
        ));
    }

    public function getParent()
    {
        return EntityType::class;
    }
}