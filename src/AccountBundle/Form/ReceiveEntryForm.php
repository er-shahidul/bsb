<?php

namespace AccountBundle\Form;

use AccountBundle\Entity\Payer;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

class ReceiveEntryForm extends AbstractType
{

    /** @var EntityManagerInterface  */
    protected $em;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->em = $entityManager;
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $data = $options['data'];
        $fundType = $data->getFundType();
        $office = $data->getOffice();
        $choices = $this->getChoicesOption($fundType);

        $builder
            ->add('receivedFrom', ChoiceType::class, [
                'required' => true,
                'choices' => $choices['toOrFrom'],
                'attr' => ['class' => 'bs-select', 'data-live-search' => 'true']
            ])
            ->add('against', ChoiceType::class, [
                'required' => true,
                'choices' => $choices['against'],
                'attr' => ['class' => 'bs-select', 'data-live-search' => 'true']
            ])
            ->add('description')
            ->add('voucherDetails', CollectionType::class, [
                'entry_type' => PaymentVoucherDetailForm::class,
            ])
            ->add('account', EntityType::class, [
                'class' => 'AccountBundle\Entity\BankAccount',
                'choice_label' => 'name',
                'query_builder' => function (EntityRepository $repository) use ($office, $fundType) {
                    return $repository->getBankAccountByOffice($office, $fundType, true);
                },
                'constraints' => [
                    new NotBlank(),
                ],
                'placeholder' => 'Select an account',
            ]);
    }
    
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AccountBundle\Entity\ReceiveVoucher',
            'attr' => [
                'enctype' => 'multipart/form-data',
                'class' => 'jq-validate',
                'id' => 'receive-entry-form',
            ],
            'translation_domain' => false
        ));
    }

    protected function getChoicesOption($fundType)
    {
        return $this->em->getRepository('AccountBundle:Payer')->getDropDownOption($fundType);
    }
}