<?php

namespace AccountBundle\Form;

use AppBundle\Entity\Office;
use AppBundle\Form\Type\DateType;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\GreaterThan;
use Symfony\Component\Validator\Constraints\NotBlank;

class PaymentEntryForm extends AbstractType
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
            ->add('amount', null, array(
                'required' => false,
                'constraints' => array(
                    new NotBlank(),
                    new GreaterThan(['value' => 0])
                ),
                'attr' => ['class' => 'amount input-small']
            ))
            ->add('description')
            ->add('fundType', EntityType::class, array(
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
                'disabled' => $data->getId(),
                'placeholder' => 'Choose an option',
            ))
        ;
    }
    
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AccountBundle\Entity\SanctionPayment',
            'translation_domain' => false
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'accountbundle_sanctionentry';
    }


}
