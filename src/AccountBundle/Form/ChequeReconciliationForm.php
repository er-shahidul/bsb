<?php

namespace AccountBundle\Form;

use AccountBundle\Entity\Reconciliation;
use AccountBundle\Entity\Voucher;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManager;
use Ivory\CKEditorBundle\Form\Type\CKEditorType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ChequeReconciliationForm extends AbstractType
{
    /**
     * @var EntityManager
     */
    private $em;

    protected $office;
    protected $fundType;

    public function __construct(EntityManager $entityManager)
    {
        $this->em = $entityManager;
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        /** @var Reconciliation $reconciliation */
        $reconciliation = $options['data'];
        $this->office = $reconciliation->getOffice();
        $this->fundType = $reconciliation->getFundType();

        $builder->add('description', CKEditorType::class);

        $builder->add('vouchers', CollectionType::class, [
            'entry_type' => ReconciliationVoucherForm::class,
        ]);

        $builder->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event) {
            $this->preSetData($event);
        });

        $builder->addEventListener(FormEvents::POST_SUBMIT, function (FormEvent $event) {
            $this->postSubmit($event);
        });

    }
    
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AccountBundle\Entity\Reconciliation',
            'allow_extra_fields' => true,
            'attr' => [
                'enctype' => 'multipart/form-data',
                'class' => 'jq-validate',
                'id' => 'reconcile-entry-form',
            ],
            'translation_domain' => false
        ));
    }

    protected function preSetData(FormEvent $event)
    {
        /** @var Reconciliation $reconciliation */
        $reconciliation = $event->getData();
        $vouchers = $this->em->getRepository('AccountBundle:PaymentVoucher')->getAllWithoutReconciliationRef($this->office, $this->fundType);

        foreach ($vouchers as $key => $voucher) {
            $reconciliation->addVoucher($voucher);
        }
    }

    protected function postSubmit(FormEvent $event)
    {
        /** @var Reconciliation $reconciliation */
        $reconciliation = $event->getData();

        $data = new ArrayCollection();
        /** @var Voucher $voucher */
        foreach ($reconciliation->getVouchers() as $voucher) {
            if ($voucher->getMarkForReconcile()) {
                $data->add($voucher);
                $voucher->setReconciliation($reconciliation);
            } else {
                $voucher->setReconciliation(null);
                $voucher->setReconciliationDate(null);
            }
        }
        $reconciliation->setVouchers($data);
    }
}
