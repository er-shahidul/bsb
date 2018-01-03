<?php

namespace FuneralBundle\Form;

use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class FuneralType extends AbstractType
{
    private $office;
    private $route;

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $this->office = $options['office'];
        $this->route = $options['route'];

         $builder
             ->add('deceasedDate', NULL, array(
                'widget' => 'single_text',
                'format' => 'y-M-d',
                'required' => true,
                'attr'   => array(
                    'placeholder' => 'Deceased Date',
                    'class' => 'date-picker',
                ),
                'label'  =>  'Start Date'
            ))
            ->add('deceasedReason', TextareaType::class, [
                'required' => false,
                'label' => 'Cause of Death'
            ])
            ->add('expenditure', TextareaType::class, [
                'required' => false,
                'label' => 'Details of Expenditure'
            ])
            ->add('deceasedPlace', TextType::class, [
                'required' => false
            ])
            ->add('burialPlace', TextType::class, [
                'required' => false
            ])
            ->add('baseAreaUnit', TextType::class, [
                'required' => false,
                'label' => 'Responsible Base/Area/Unit'
            ])
            ->add('amount', TextType::class, [
                'required' => true
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
            'data_class' => 'FuneralBundle\Entity\Funeral',
            'office' => null,
            'route' => null,
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'funeral';
    }


}
