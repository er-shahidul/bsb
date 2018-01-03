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
class NotificationRecipientDatatable extends BaseDatatable
{
    const FROM_COLUMN_TEMPLATE = '<img src="/%s" height="20">&nbsp;%s';

    public function getLineFormatter()
    {
        $formatter = function($line){
            $notification = $line['notification'];
            $line['notification']['senderName'] = sprintf(self::FROM_COLUMN_TEMPLATE, $this->getSenderImage($notification), $notification['senderName']);

            return $line;
        };

        return $formatter;
    }

    public function getSenderImage($item)
    {
        $sender = $item['sender'];

        if ($item['notification_type'] == 'office') {
            return 'assets/global/img/office.png';
        } elseif ($item['notification_type'] == 'user') {
            return empty($sender['image']) ? Personnel::DEFAULT_AVATAR : $sender['image'];
        }

        return 'assets/global/img/system.png';
    }

    /**
     * {@inheritdoc}
     */
    public function configureDataTable(array $options = array())
    {
        $this->actionButtonType = 'flat';
        $this->features->set($this->getDefaultFeatures());
        $this->ajax->set($this->getDefaultAjax(['url' => $this->router->generate('correspondence_inbox')]));
        $this->options->set($this->getDefaultOptions(['order' => [[6, 'desc']]]));
        $this->callbacks->set(
            [
                'row_callback' => array(
                    'template' => '@Notification/_data_table/_row_callback.js.twig',
                )
            ]
        );

        $this->addActionButton(null, 'View', 'glyphicon-eye-open', ['id' => 'id']);

        $this->columnBuilder
            ->add('notification.sender', Column::class, array(
                'visible' => false,
                ))
            ->add('notification.senderName', Column::class, array(
                'visible' => false,
                ))
            ->add('seen', Column::class, array(
                'visible' => false,
            ))
            ->add('notification.senderName', Column::class, array(
                'title' => 'From',
            ))
            ->add('notification.subject', Column::class, array(
                'title' => 'Subject',
                ))
            ->add('notification.link', Column::class, array(
                'visible' => false,
                ))
            ->add('notification.date', DateTimeColumn::class, array(
                'title' => 'Received',
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
        return 'NotificationBundle\Entity\NotificationRecipient';
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'notificationrecipient_datatable';
    }
}
