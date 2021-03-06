<?php

namespace WelfareBundle\Datatables;

use AppBundle\Datatables\BaseDatatable;
use BudgetBundle\Datatables\Column\HumanizeTextColumnDeprecated;
use Sg\DatatablesBundle\Datatable\Column\Column;
use Sg\DatatablesBundle\Datatable\Column\DateTimeColumn;
use Sg\DatatablesBundle\Datatable\Column\NumberColumn;
use Sg\DatatablesBundle\Datatable\Column\VirtualColumn;
use Sg\DatatablesBundle\Datatable\Filter\SelectFilter;
use Sg\DatatablesBundle\Datatable\Filter\TextFilter;

/**
 * Class MicroCreditApplicationDatatable
 *
 * @package WelfareBundle\Datatables
 */
class MicroCreditApplicationDatatable extends BaseDatatable
{
    public function getLineFormatter()
    {
        $formatter = function($line){
            $line['applicant'] = $line['serviceMan']['identityNumber'].'<br/>'.
                $line['serviceMan']['name'].'<br/>'.$line['serviceMan']['rank']['name'];
            $line['meeting']['subject'] = ($line['meeting']['subject'] === null) ? 'N/A' : $line['meeting']['subject'];
            return $line;
        };

        return $formatter;
    }

    /**
     * {@inheritdoc}
     */
    public function configureDataTable(array $options = array())
    {
        $this->actionButtonType = 'flat';
        $this->features->set($this->getDefaultFeatures());
        $this->options->set($this->getDefaultOptions([
            'individual_filtering' => true,
            'order_cells_top' => true
        ]));
        $this->ajax->set($this->getDefaultAjax(['url' => $this->router->generate('welfare_micro_credit_index')]));
        $this->setDefaultExportButtons([0,2,7,8,9]);

        $this->addActionButton('welfare_micro_credit_view', 'View', 'glyphicon-eye-open', ['id' => 'id']);

        if ($this->authorizationChecker->isGranted(['ROLE_DASB_CLERK'])) {
            $this->addActionButton('welfare_micro_credit_edit', 'Edit', 'glyphicon-edit', ['id' => 'id'], function($row) {
                return HumanizeTextColumnDeprecated::reformStatusValue($row['status']) == 'draft' || empty($row['status']);
            });
        }

        $meetingTypes = $this->em->getRepository('BoardMeetingBundle:BoardMeeting')->findBy([
            'type' => 'micro-credit'
        ]);
        $projectTypes = $this->em->getRepository('WelfareBundle:MicroCreditProjectType')->findAll();
        $officeTypes = $this->em->getRepository('AppBundle:Office')->createQueryBuilder('o')
            ->join('o.officeType', 't')->where('t.name = :office')->setParameter('office', 'DASB')
            ->getQuery()->getResult();

        $this->columnBuilder

            ->add('office.name', Column::class, array(
                'title' => 'DASB',
                'filter' => array(SelectFilter::class, array(
                    'search_type' => 'eq',
                    'select_options' => array('' => 'All') + $this->getOptionsArrayFromEntities($officeTypes, 'name', 'name'),
                )),
            ))
            ->add('meeting.subject', Column::class, array(
                'title' => 'Meeting',
                'filter' => array(SelectFilter::class, array(
                    'search_type' => 'eq',
                    'select_options' => array('' => 'All') + $this->getOptionsArrayFromEntities($meetingTypes, 'subject', 'subject'),
                )),
            ))
            ->add('applicant', VirtualColumn::class, array(
                'title' => 'Applicant',
                'searchable' => true,
                'orderable' => false,
                'search_column' => 'serviceMan.identityNumber',
                'filter' => array(TextFilter::class, array(
                    'placeholder_text' => 'Soldier Id'
                )),

            ))
            ->add('serviceMan.name', Column::class, array(
                'visible' => false,
            ))
            ->add('serviceMan.rank.name', Column::class, array(
                'visible' => false
            ))
            ->add('serviceMan.identityNumber', Column::class, array(
                'visible' => false
            ))
            ->add('microCreditDetail.totalPaid', Column::class, array(
                'visible' => false
            ))
            ->add('microCreditDetail.projectType.id', Column::class, array(
                'title' => 'Project Type',
                'searchable' => true,
                'orderable' => true,
                'filter' => array(SelectFilter::class, array(
                    'search_type' => 'eq',
                    'select_options' => array('' => 'All') + $this->getOptionsArrayFromEntities($projectTypes, 'id', 'id'),
                )),
            ));
        $this->addNumberColumn('microCreditDetail.requestAmount', 'Requested(TK)');
        $this->addNumberColumn('amount', 'Granted(TK)');
        $this->columnBuilder
            ->add('createdAt', DateTimeColumn::class, array(
                'title' => 'Application Date',
                'date_format' => 'DD-MM-YYYY',
            ));
        $this->addStatusColumn('status', 'Status');
        $this->addStatusColumn('grantStatus', 'Grant Status');

        $this->initActionButtons();
    }

    /**
     * {@inheritdoc}
     */
    public function getEntity()
    {
        return 'WelfareBundle\Entity\MicroCreditApplication';
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'microcreditapplication_datatable';
    }
}
