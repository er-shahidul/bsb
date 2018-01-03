<?php

namespace LeaveBundle\Form;

use LeaveBundle\Entity\Property;
use Doctrine\ORM\EntityRepository;
use PersonnelBundle\Entity\ServingCivilian;
use PersonnelBundle\Entity\ServingMilitary;
use PersonnelBundle\Entity\ServingPersonnel;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class LeaveType extends AbstractType
{
    const SERVING_TYPE_CIVILIAN = 1;
    const SERVING_TYPE_MILITARY = 2;

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $office = $options['office'];

        $builder
            ->add('identityNumber', EntityType::class, [
                'class'         => $this->getEntityClass($options['servingType']),
                'label'         => 'Personal No',
                'choice_label' => 'identityNumber',
                'query_builder' => function (EntityRepository $er) use ($office) {
                    return $er
                        ->createQueryBuilder('sp')
                        ->where('sp.office = :office')
                        ->setParameter('office', $office);
                },
                'attr' => [
                    'class' => 'bs-select',
                    'data-live-search' => 'true',
                    'data-size' => '8'
                ],
                'placeholder' => 'Personal No',
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
            ->add('details', TextareaType::class, [
                'required' => false,
            ])
            ->add('subject', TextType::class, [
                'required' => true,
            ])
            ->add('numberOfDate', TextType::class, [
                'required' => true,
                'label' => 'Number Of Days'
            ])
            ->add('typeOfLeave', ChoiceType::class, array(
                'label' => 'Leave Type',
                'choices' => Property::LEAVE_TYPE,
                'required' => true,
                'placeholder' => 'Leave Type',
            ))
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
            'data_class' => 'LeaveBundle\Entity\Leave',
            'office' => null,
            'servingType' => null,
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'leave_form';
    }

    /**
     * @param $servingType
     * @return mixed
     */
    public function getEntityClass($servingType)
    {
        switch ($servingType) {
            case self::SERVING_TYPE_CIVILIAN:
                $entity = ServingCivilian::class;
                break;
            case self::SERVING_TYPE_MILITARY:
                $entity = ServingMilitary::class;
                break;
            default:
                $entity = ServingPersonnel::class;
                return $entity;
        }
        return $entity;
    }
}
