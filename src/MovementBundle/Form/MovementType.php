<?php

namespace MovementBundle\Form;

use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class MovementType extends AbstractType
{
    private $office;

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $this->office = $options['office'];

        $builder
            ->add('visitors', EntityType::class, [
                'class'         => 'PersonnelBundle\Entity\ServingPersonnel',
                'label'         => 'Visitors',
                'query_builder' => function (EntityRepository $er) {
                    $qb = $er->createQueryBuilder('sp');
                    $qb = $qb->join('sp.office', 'office')
                        ->where('office.id = :office')
                        ->setParameter('office', $this->office);
                    return $qb;
                },
                'multiple' => true,
                'required' => true
            ])
            ->add('destinations', EntityType::class, [
                'class'         => 'AppBundle\Entity\Office',
                'label'         => 'Destination',
                'multiple' => true,
                'required' => true
            ])
            ->add('startDate', NULL, array(
                'widget' => 'single_text',
                'format' => 'y-M-d',
                'required' => true,
                'attr'   => array(
                    'placeholder' => 'Start Date',
                    'class' => 'date-picker',
                ),
                'label'  =>  'Start Date'
            ))
            ->add('endDate', NULL, array(
                'widget' => 'single_text',
                'format' => 'y-M-d',
                'required' => true,
                'attr'   => array(
                    'placeholder' => 'End Date',
                    'class' => 'date-picker',
                ),
                'label'  =>  'End Date'
            ))
            ->add('startPoint', TextType::class, [
                'required' => false,
                'attr' => [
                    'class' => 'typeahead',
                    'placeholder' => 'Start Point',
                ]
            ])
            ->add('description', TextareaType::class, [
                'required' => false,
            ])
            ->add('movementApproval', TextareaType::class, [
                'required' => false,
            ])
            ->add('transportation', TextareaType::class, [
                'required' => false,
                'label' => 'Movement Transportation'
            ])
            ->add('travelPlan', TextareaType::class, [
                'required' => false,
            ])
            ->add('additionalMembers', TextareaType::class, [
                'required' => false,
            ])
            ->add('travelBy', TextType::class, [
                'required' => false
            ])
            ->add('submit', SubmitType::class, array(
                'attr' => array('class' => 'btn green col-md-12')
            ))
        ;
    }
    
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'MovementBundle\Entity\Movement',
            'office' => null
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'movement_form';
    }
}
