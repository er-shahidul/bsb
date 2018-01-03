<?php

namespace AccountBundle\Form\Report;

use AppBundle\Entity\Office;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

class VoucherReportForm extends AbstractType
{
    /** @var  EntityManagerInterface */
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
        /** @var Office $office */
        $fundType = isset($options['data']['fundType']) ? $options['data']['fundType'] : null;
        $choices = $this->getChoicesOption($fundType, $options['type']);
        $builder
            ->add('toOrFrom', ChoiceType::class, [
                'required' => true,
                'choices' => $choices['toOrFrom'],
                'attr' => ['class' => 'bs-select', 'data-live-search' => 'true']
            ])
            ->add('against', ChoiceType::class, [
                'required' => true,
                'multiple' => true,
                'choices' => $choices['against'],
                'attr' => ['class' => 'bs-select', 'data-live-search' => 'true']
            ])
            ->add('startDate', TextType::class, [
                'attr' => [
                    'class' => 'date-picker input-msmall'
                ]
            ])
            ->add('endDate', TextType::class, [
                'attr' => [
                    'class' => 'date-picker input-msmall'
                ]
            ])
            ->add('fundType', ChoiceType::class, array(
                'required' => true,
                'choices' => $this->fundType($options['office']),
                'constraints' => array(
                    new NotBlank()
                ),
                'placeholder' => 'Select a fund'
            ))
            ->add('title', TextType::class, [
                'required' => false
            ]);
    }
    
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'method' => 'get',
            'attr' => [
                'class' => 'jq-validate form hidden-print'
            ],
            'office' => null,
            'type' => null,
            'translation_domain' => false
        ));
    }

    /**
     * @param $fundType
     * @param $type string receive|payment
     * @return array
     */
    protected function getChoicesOption($fundType, $type)
    {
        $type = ucfirst($type);
        return $this->em->getRepository("AccountBundle:{$type}")->getDropDownOption($fundType, $type);
    }

    protected function fundType(Office $office)
    {
        return $this->em->getRepository('AccountBundle:FundType')->getOfficeFundTypeAsArray(!$office->isBasb());
    }
}
