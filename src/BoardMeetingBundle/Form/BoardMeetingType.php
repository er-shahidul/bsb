<?php

namespace BoardMeetingBundle\Form;

use AppBundle\Form\Type\DateType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

class BoardMeetingType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    final public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('subject', TextType::class, ['required' => TRUE])
            ->add('date', DateType::class, array(
                'widget' => 'single_text',
                'format' => 'y-M-d',
                'attr'   => array(
                    'placeholder'          => 'Meeting Date',
                    'class'                => 'date-picker',
                    'data-date-start-date' => 'today'
                ),
                'label'  => 'Meeting Date'
            ))
            ->add('entity', HiddenType::class, [
                'required' => TRUE,
                'constraints' => [
                    new NotBlank()
                ]
            ])
            ->add('members', CollectionType::class, [
                'label'        => 'Board Members',
                'allow_add'    => TRUE,
                'delete_empty' => TRUE,
                'allow_delete' => TRUE,
                'entry_type'   => BoardMemberType::class,
                'by_reference' => FALSE
            ]);;
    }

    /**
     * {@inheritdoc}
     */
    final public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'BoardMeetingBundle\Entity\BoardMeeting',
            'attr'       => [
                'novalidate' => 'novalidate',
                'id'         => 'board-meeting-form',
                'class'      => 'jq-validate'
            ],
            'method'     => 'POST',
        ));
    }

    /**
     * {@inheritdoc}
     */
    final public function getBlockPrefix()
    {
        return 'board_meeting';
    }
}
