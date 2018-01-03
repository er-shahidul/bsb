<?php

namespace PersonnelBundle\Form;

use Doctrine\DBAL\Types\TextType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class FamilyInformationType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', NULL, [
                'label' => 'নাম',
                'attr' => array(
                    'placeholder' => 'নাম',
                ),
            ])
            ->add('dateOfBirth', null, array(
                'widget' => 'single_text',
                'format' => 'y-M-d',
                'attr' => array(
                    'class' => 'date-picker',
                    'data-date-end-date' => "today",
                     'placeholder' => 'জন্ম তারিখ'
                ),
                'label' => 'জন্ম তারিখ'
            ))
            ->add('occupation', NULL, [  'label' => 'পেশা',
                'attr' => array(
                    'placeholder' => 'পেশা',
                )
            ])
            ->add('mobileNumber', NULL, [ 'label' => 'যোগাযোগের নম্বর',
                'attr' => array(
                    'placeholder' => 'যোগাযোগের নম্বর',
                )
            ])
            ->add('nokPercentage', NULL, [
                'label' => 'এনওকে%',
                'attr' => array(
                    'placeholder' => 'এনওকে%',
                )
            ])
            ->add('address', NULL, [ 'label' => 'ঠিকানা',
                'attr' => array(
                    'placeholder' => 'ঠিকানা',
                )
            ])
            ->add('nidOrBirthCertificate', NULL, [ 'label' => 'জন্ম নিবন্ধন/ জাতীয় পরিচয়পত্র নম্বর',
                'attr' => array(
                    'placeholder' => 'জন্ম নিবন্ধন/ জাতীয় পরিচয়পত্র নম্বর',
                )
            ])
            ->add('relationType', EntityType::class, [
                'class' => 'PersonnelBundle\Entity\RelationType',
                'required'    => TRUE,
                'placeholder' => 'সম্পর্ক',
                'label' => 'সম্পর্ক'
            ])
        ;
    }
    
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'PersonnelBundle\Entity\FamilyInformation'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'familyinformation';
    }


}
