<?php

namespace BudgetBundle\Form;

use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

class BudgetHeadForm extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $data = $options['data'];

        $builder->add('code', null, [
            'required' => false,
            'constraints' => [
                new NotBlank()
            ],
        ]);
        $builder->add('titleEn', null, [
            'label' => 'Title English',
            'required' => false,
            'constraints' => [
                new NotBlank()
            ],
        ]);
        $builder->add('titleBn', null, [
            'label' => 'Title Bangla',
            'required' => false,
            'constraints' => [
                new NotBlank()
            ],
        ]);
        $builder->add('sort', null, [
            'label' => 'Sort',
            'required' => false,
            'constraints' => [
                new NotBlank()
            ],
        ]);
        $builder->add('star', ChoiceType::class, [
            'label' => 'Star',
            'required' => false,
            'choices' => [
                1 => 1, 2 => 2, 3 => 3
            ]
        ]);
        $builder->add('parent', null, [
            'query_builder' => function(EntityRepository $er) use ($data) {
                /** @var \Doctrine\ORM\QueryBuilder $qb */
                $qb = $er->getParentBudgetHeadQb();
                if ($data && $data->getId()) {
                    $qb->andWhere($qb->expr()->neq('b.id', $data->getId()));
                }

                return $qb;
            },
        ]);
    }
    
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'BudgetBundle\Entity\BudgetHead'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'budgetbundle_budgethead';
    }


}
