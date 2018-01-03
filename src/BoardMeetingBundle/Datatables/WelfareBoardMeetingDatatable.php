<?php

namespace BoardMeetingBundle\Datatables;

use AppBundle\Datatables\BaseDatatable;
use BudgetBundle\Datatables\Column\HumanizeTextColumnDeprecated;
use Sg\DatatablesBundle\Datatable\Column\Column;
use Sg\DatatablesBundle\Datatable\Column\DateTimeColumn;
use Sg\DatatablesBundle\Datatable\Column\VirtualColumn;

/**
 * Class WelfareBoardMeetingDatatable
 *
 * @package BoardMeetingBundle\Datatables
 */
class WelfareBoardMeetingDatatable extends BaseBoardMeetingDatatable
{
    /**
     * {@inheritdoc}
     */
    public function configureDataTable(array $options = array())
    {
        $this->actionButtonType = 'flat';
        $this->ajax->set($this->getDefaultAjax(['url' => $this->router->generate('board_meetings_welfare')]));
        $this->features->set($this->getDefaultFeatures());
        $this->options->set($this->getDefaultOptions());
        $this->setDefaultExportButtons([1,2,3]);

        $this->addActionButton('board_meeting_view', 'View', 'glyphicon-eye-open', ['id' => 'id']);
        $this->addActionButton('board_meeting_edit', 'Edit', 'glyphicon-edit', ['id' => 'id'], function($row){
            return HumanizeTextColumnDeprecated::reformStatusValue($row['status']) == 'draft' && $this->authorizationChecker->isGranted(['ROLE_WELFARE_CLERK']);
        });

        $this->columnBuilder
            ->add('id', Column::class, array(
                'visible' => FALSE,
            ))
            ->add('subject', Column::class, array(
                'title' => 'Subject',
                ))
            ->add('date', DateTimeColumn::class, array(
                'title' => 'Meeting Date',
                'date_format' => 'YYYY-MM-DD'
                ))
            ->add('chairman.name', Column::class, array(
                'visible' => FALSE,
                ))
            ->add('chairman.designation', Column::class, array(
                'visible' => FALSE,
                ))
            ->add('chairmanData', VirtualColumn::class, array(
                'title' => 'Chairman',
                ))
            ->add('status', HumanizeTextColumnDeprecated::class, array(
                'title' => 'Status',
            ))
            ;

        $this->initActionButtons();
    }
}
