<?php

namespace WelfareBundle\Form;

use Ivory\CKEditorBundle\Form\Type\CKEditorType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use WelfareBundle\Entity\MicroCreditApplicationDetail;

class MicroCreditApplicationForm extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('note', CKEditorType::class, [
                'required' => false,
                'config' => array('toolbar' => 'welfare_wysiwyg'),
                'attr' => [
                    'class' => 'form-control',
                    'rows' => 10,
                    'cols' => 55
                ],
            ])
            ->add('microCreditDetail', MicroCreditApplicationDetailForm::class)
        ;
    }
    
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'WelfareBundle\Entity\MicroCreditApplication'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'welfarebundle_microcreditapplication';
    }
}
