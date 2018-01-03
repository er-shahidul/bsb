<?php

namespace AppBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use AppBundle\Repository\OfficeTypeRepository;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class OfficeType extends AbstractType
{
    private $switch;
    private $hq;

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $this->switch = $options['switch'];
        $this->hq = $options['hq'];


            if(!$this->switch){
                $builder
                ->add('officeType', EntityType::class, [
                    'class' => 'AppBundle\Entity\OfficeType',
                    'required' => true,
                    'disabled' => $this->switch,
                    'label' => 'Office Type',
                    'placeholder' => 'Select Office Type',
                    'query_builder' => $this->switch ? NULL :
                        function (OfficeTypeRepository $repository) {
                            return $this->hq=="true" ? $repository->createQueryBuilder('ot')->where(
                                $this->switch ? "ot.name = 'HQ'" : "ot.name != 'HQ'")
                                : $repository->createQueryBuilder('ot');}

                ]);
            }

        $builder
            ->add('name', NULL, [
                'required' => true,
                'label' => 'Office Name',
                'attr' => [
                    'placeholder' => 'Office Name'
                ]
            ])
            ->add('address', NULL, [
                'required' => true
            ])
            ->add('phone')
            ->add('mobile', NULL, [
                'required' => true
            ])
            ->add('fax')
            ->add('email', EmailType::class, [
                'required' => true
            ])
            ->add('geoCode')
            ->add('submit', SubmitType::class, array(
            'attr' => array('class' => 'btn green')
            ))
        ;
    }
    
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Office',
            'hq' => null,
            'switch' => null,
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'appbundle_office';
    }
}
