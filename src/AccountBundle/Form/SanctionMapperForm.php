<?php

namespace AccountBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SanctionMapperForm extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $data = $options['data'];
        $fundTypeOption = $options['fundTypeOption'];

        $builder
            ->add('fundType', ChoiceType::class, array(
                'required' => true,
                'choices' => $fundTypeOption,
                'placeholder' => 'Select Fund'
            ))
            ->add('description', TextareaType::class, array(
                'required' => false
            ))
            ->add('voucherDate', TextType::class, array(
                'attr' => [
                    'class' => 'date-picker input-small'
                ],
                'required' => true
            ))
            ;
    }
    
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AccountBundle\Mapper\Sanction',
            'attr' => [
                'class' => 'jq-validate'
            ],
            'allow_extra_fields' => true,
            'fundTypeOption' => null,
            'translation_domain' => false
        ));
    }
}
