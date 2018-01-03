<?php

namespace BoardMeetingBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class BoardMemberType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, ['required' => TRUE])
            ->add('email', EmailType::class, ['required' => TRUE])
            ->add('mobileNo', TextType::class, ['required' => TRUE])
            ->add('designation', TextType::class, ['required' => TRUE])
            ->add('chairman', CheckboxType::class, [
                'required' => FALSE,
                'attr'  => ['class' => 'chairman-type-check make-switch']
            ]);
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'BoardMeetingBundle\Entity\BoardMember'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'member';
    }
}
