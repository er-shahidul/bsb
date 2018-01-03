<?php

namespace PersonnelBundle\Form\ReportForm;

use Doctrine\ORM\EntityRepository;
use PersonnelBundle\Entity\ExServiceman;
use PersonnelBundle\Entity\ServingCivilian;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ServingCivilianReportForm extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('religion', EntityType::class, [
                'class' => 'PersonnelBundle\Entity\Religion',
                'required' => false,
                'attr' => [
                    'class' => 'bs-select',
                    'data-live-search' => 'true',
                    'data-size' => '4'
                ],
                'placeholder' => 'Select Religion.'
            ])
            ->add('gender', EntityType::class, [
                'class' => 'PersonnelBundle\Entity\Gender',
                'required' => false,
                'placeholder' => 'Select Gender'
            ])
            ->add('identityNumber', TextType::class, [
                'required' => false,
                'attr' => [
                    'class' => 'typeahead',
                    'placeholder' => 'Personal No',

                ],
                'label'         => 'Personal No',
            ])
            ->add('office', EntityType::class, [
                'class'         => 'AppBundle\Entity\Office',
                'placeholder' => 'Select Office',
                'attr' => [
                    'class' => 'bs-select',
                    'data-live-search' => 'true',
                    'data-size' => '4'
                ],
                'label'         => 'Office',
                'query_builder' => function (EntityRepository $er) {
                    $qb = $er->createQueryBuilder('office');
                    $qb = $qb->join('office.officeType', 't')
                        ->where('t.name = :type')
                        ->setParameter('type', 'DASB');
                    return $qb;
                },
            ])
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'csrf_protection' => false,
        ));
    }

    /**
     * @return string
     */
    public function getBlockPrefix()
    {
        return 'search';
    }
}
