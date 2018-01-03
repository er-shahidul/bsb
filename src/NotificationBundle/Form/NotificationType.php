<?php

namespace NotificationBundle\Form;

use Ivory\CKEditorBundle\Form\Type\CKEditorType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use UserBundle\Entity\User;
use UserBundle\Repository\UserRepository;

class NotificationType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $user = $options['user'];

        $builder
            ->add('users', EntityType::class, [
                'class' => User::class,
                'query_builder' => function(UserRepository $repository)use($user) {
                    return $repository->getUsersWithoutAdministrator($user);
                },
                'group_by' => 'office',
                'multiple'=>TRUE,
                'attr' => [
                    'class'=> 'bs-select selectpicker',
                    'data-live-search'=> true,
                    'data-size' => 8,
                    'data-selected-text-format'=>"count > 5",
                    'data-count-selected-text' => "{0} of {1} Users selected",
                    'title' => 'Select recipients',
                    'data-error-container' => '#recipients-error-span'
                ]
            ])
            ->add('subject', TextType::class, [
                'required' => TRUE,
            ])
            ->add('message', CKEditorType::class, [
                'required' => TRUE,
                'config_name' => 'minimal',
                'config'      => [
                    'autoParagraph' => FALSE,
                    'enterMode'     => 'CKEDITOR.ENTER_BR',
                ]
            ])
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'NotificationBundle\Mapper\NotificationData',
            'user' => NULL,
            'attr' => [
                'class' => 'jq-validate',
                'id' => 'new-correspondence-form'
            ]
        ));
    }
}