<?php

namespace NotificationBundle\Datatables;

use AppBundle\Datatables\BaseDatatable;
use PersonnelBundle\Entity\Personnel;
use Sg\DatatablesBundle\Datatable\Column\Column;
use Sg\DatatablesBundle\Datatable\Column\DateTimeColumn;

/**
 * Class NotificationRecipientDatatable
 *
 * @package NotificationBundle\Datatables
 */
class NotificationSentDatatable extends BaseDatatable
{
    const FROM_COLUMN_TEMPLATE = '<img src="/%s" height="20">&nbsp;%s';

    /**
     * {@inheritdoc}
     */
    public function configureDataTable(array $options = array())
    {
        $this->actionButtonType = 'flat';
        $this->features->set($this->getDefaultFeatures());
        $this->ajax->set($this->getDefaultAjax(['url' => $this->router->generate('correspondence_sent')]));
        $this->options->set($this->getDefaultOptions(['order' => [[3, 'desc']]]));

        $this->addActionButton(null, 'View', 'glyphicon-eye-open', ['id' => 'id']);

        $this->columnBuilder
            ->add('senderName', Column::class, array(
                'title' => 'Sent as',
            ))
            ->add('subject', Column::class, array(
                'title' => 'Subject',
                ))
            ->add('link', Column::class, array(
                'visible' => false,
                ))
            ->add('date', DateTimeColumn::class, array(
                'title' => 'Sent at',
                'date_format' => 'LLL',
                ))
            ;

        $this->initActionButtons();
    }

    /**
     * {@inheritdoc}
     */
    public function getEntity()
    {
        return 'NotificationBundle\Entity\UserNotification';
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'notification_sent_datatable';
    }
}
