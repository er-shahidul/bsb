<?php

namespace AccountBundle\Form;

use AccountBundle\Entity\ChequeIssue;
use AccountBundle\Form\DataTransformer\SanctionEntryTransformer;
use AppBundle\Form\Type\DateType;
use Doctrine\ORM\EntityManager;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ChequeIssueForm extends AbstractType
{
    /** @var  EntityManager */
    public $em;

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $this->em = $options['em'];

        $builder->add('chequeDate', DateType::class);
        $builder->add('sanctions', ChoiceType::class, [
            'choices' => $this->getChoices($options['data'], 'payment'),
            'multiple' => true
        ]);

        $builder->get('sanctions')->addModelTransformer(new SanctionEntryTransformer($this->em));
    }
    
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AccountBundle\Entity\ChequeIssue',
            'allow_extra_fields' => true,
            'em' => null,
            'translation_domain' => false
        ));
    }

    public function getChoices(ChequeIssue $chequeIssue, $type)
    {
        $fundType = $chequeIssue->getFundType();
        $officeId = $chequeIssue->getOffice()->getId();

        $unusedSanctionSql = "SELECT
account_sanction_register.id,
account_sanction_register.note_sheet_number,
account_sanction_register.amount
FROM
account_sanction_register
LEFT JOIN cheque_issue_sanction_entry ON cheque_issue_sanction_entry.sanction_entry_id = account_sanction_register.id
WHERE
account_sanction_register.sanction_type = '{$type}' 
AND account_sanction_register.fund_type_id = {$fundType->getId()}
AND account_sanction_register.office_id = {$officeId}
AND cheque_issue_sanction_entry.sanction_entry_id IS NULL
AND account_sanction_register.status = 'approved'
";
        $unusedSanctions = $this->em->getConnection()->query($unusedSanctionSql)->fetchAll();

        $data = [];

        foreach ($unusedSanctions as $row) {
            $data[sprintf('%s - %s', $row['note_sheet_number'], $row['amount'])] = $row['id'];
        }

        foreach ($chequeIssue->getSanctions() as $sanctionEntry) {
            $data[$sanctionEntry->getNoteSheetAndAmount()] = $sanctionEntry->getId();
        }

        return $data;
    }
}
