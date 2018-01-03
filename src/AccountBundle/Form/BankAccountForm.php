<?php

namespace AccountBundle\Form;

use AppBundle\Entity\Office;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

class BankAccountForm extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $data = $options['data'];
        /** @var Office $office */
        $office = $data->getOffice();
        $officeType = $office->getOfficeType()->getName();

        $builder
            ->add('name', null, array(
                'required' => true,
                'constraints' => array(
                    new NotBlank()
                )
            ))
            ->add('accountNumber', null, array(
                'required' => true,
                'constraints' => array(
                    new NotBlank()
                )
            ))
            ->add('bankBranch', null, array(
                'required' => true,
                'choice_label' => 'nameWithBank',
                'attr' => ['class' => 'bs-select', 'data-live-search' => 'true', 'data-size' => '8'],
                'placeholder' => 'Select a Bank/Branch'
            ))
            ->add('fundType', null, array(
                'required' => true,
                'class' => 'AccountBundle\Entity\FundType',
                'query_builder' => function(EntityRepository $repository) use ($officeType){
                    $qb = $repository->createQueryBuilder('f');
                    if ($officeType == 'DASB') {
                        $qb->where('f.basbFund = :basbFund')->setParameter('basbFund', false);
                        $qb->orderBy('f.sort', 'asc');
                    }

                    return $qb;
                },
                'constraints' => array(
                    new NotBlank()
                ),
                'placeholder' => 'Select a fund'
            ));
    }
    
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AccountBundle\Entity\BankAccount',
            'attr' => [
                'class' => 'jq-validate'
            ],
            'translation_domain' => false
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'accountbundle_bankaccount';
    }


}
