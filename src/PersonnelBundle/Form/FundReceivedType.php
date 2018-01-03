<?php

namespace PersonnelBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;

class FundReceivedType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('amount')
            ->add('readonly', HiddenType::class)
            ->add('date', null, array(
                'widget' => 'single_text',
                'format' => 'y-M-d',
                'attr' => array(
                    'class' => 'date-picker',
                    'data-date-end-date' => "today"
                ),
                'label' => 'Received date'
            ))
            ->add('fundType', NULL, [
                'required'    => TRUE,
                'placeholder' => 'Select Type',
            ])
        ;

        $builder->addEventListener(
            FormEvents::PRE_SET_DATA,
            function (FormEvent $event) {
                $data = $event->getData();
                $form = $event->getForm();

                if($data !== null && $data->isSystemGenerated()) {
                    $form
                        ->remove('amount')
                        ->remove('date')
                        ->remove('fundType')
                    ;
                }
            }
        );
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'PersonnelBundle\Entity\FundReceived'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'personnelbundle_fundreceived';
    }
}
