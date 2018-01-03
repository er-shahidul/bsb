<?php

namespace WelfareBundle\Form;


use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Range;
use WelfareBundle\Validator\Constraints\BSCRMaxGrant;
use WelfareBundle\Validator\Constraints\BSCRMaxGrantValidator;
use WelfareBundle\Validator\Constraints\MicroCreditMaxGrant;
use WelfareBundle\Validator\Constraints\RCELMaxGrant;

class MeetingApplicationCommentForm extends AbstractType
{
    private $memberComment = '';
    private $installmentAmount = 0;

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $application = $options['application'];
        $member = $options['member'];
        $isChairman = $options['isChairman'];
        $this->installmentAmount = $options['installmentAmount'];

        if ($application != null && $member != null) {
            $comments = $application->getMemberComments();
            $this->memberComment = isset($comments[$member->getId()]['comment']) ? $comments[$member->getId()]['comment'] : '' ;
        }

        if ($application->getType() == 'micro-credit' && $application->getMicroCreditDetail()->getInstallmentAmount()) {
            $this->installmentAmount = $application->getMicroCreditDetail()->getInstallmentAmount();
        }

        $builder
            ->add('memberComments', TextareaType::class, [
                'label' => 'Your Comment',
                'attr' => ['placeholder' => 'Write here'],
                'data' => $this->memberComment,
                'constraints' => array(
                    new NotBlank(),
                )
            ]);
        if ($isChairman) {
            $this->grantAmount($builder, $application);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'WelfareBundle\Entity\WelfareApplication',
            'application' => null,
            'member' => null,
            'isChairman' => false,
            'installmentAmount' => 0,
            'add_attachment' => false,
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'welfarebundle_application_comment';
    }

    /**
     * @param FormBuilderInterface $builder
     * @param $application
     */
    private function grantAmount(FormBuilderInterface $builder, $application)
    {
        switch ($application->getType()) {
            case 'rcel':
                $builder
                    ->add('amount', null, [
                        'label' => 'Grant Amount',
                        'attr' => ['class' => 'input-small grant-amount'],
                        'constraints' => array(
                            new RCELMaxGrant(),
                        )
                    ]);
                break;
            case 'micro-credit':
                $builder
                    ->add('amount', null, [
                        'label' => 'Grant Amount',
                        'attr' => ['class' => 'input-small grant-amount'],
                        'constraints' => array(
                            new MicroCreditMaxGrant(),
                        )
                    ])
                    ->add('microCreditDetail', MicroCreditApplicationDetailAmountForm::class, ['installmentAmount' => $this->installmentAmount])
                ;
                break;
            case 'bscr':
                $builder
                    ->add('amount', null, [
                        'label' => 'Grant Amount',
                        'attr' => ['class' => 'input-small grant-amount'],
                        'constraints' => array(
                            new BSCRMaxGrant(),
                        )
                    ]);

        }
    }
}
