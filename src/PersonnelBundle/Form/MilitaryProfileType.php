<?php

namespace PersonnelBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MilitaryProfileType extends AbstractType
{
    use RankCorpModifierTrait;

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
//        $builder
//            ->add('regimentalNumber', NULL, [
//                'label' => 'রেজিমেন্ট নম্বর',
//                'attr' => [
//                    'placeholder' => 'রেজিমেন্ট নম্বর'
//                ]
//            ])
//        ;

        $this->modifyRankAndCorp($builder, TRUE);
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'PersonnelBundle\Entity\MilitaryProfile'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'militaryprofile';
    }
}
