<?php

namespace WelfareBundle\Form;

use Doctrine\ORM\EntityRepository;
use Ivory\CKEditorBundle\Form\Type\CKEditorType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MicroCreditApplicationDetailForm extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('projectType', EntityType::class, [
                'class' => 'WelfareBundle\Entity\MicroCreditProjectType',
                'attr' => ['class' => 'input-xlarge'],
                'query_builder' => function(EntityRepository $repo) {
                    return $repo->createQueryBuilder('m')
                        ->orderBy('m.sort', 'ASC')
                        ;
                }
            ])
            ->add('projectName', null, [
                'attr' => ['class' => 'input-xlarge']
            ])
            ->add('requestAmount', NumberType::class, [
                'attr' => [
                    'class' => 'input-xlarge amount',

                ],
                'label' => 'Request Amount (TK)'
            ])
        ;
    }
    
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'WelfareBundle\Entity\MicroCreditApplicationDetail'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'welfarebundle_microcreditapplication_detail';
    }
}
