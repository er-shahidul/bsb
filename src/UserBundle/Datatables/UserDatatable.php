<?php

namespace UserBundle\Datatables;

use AppBundle\Datatables\BaseDatatable;
use AppBundle\Datatables\Column\ProfileImageColumn;
use Sg\DatatablesBundle\Datatable\Column\BooleanColumn;
use Sg\DatatablesBundle\Datatable\Column\Column;

/**
 * Class UserDatatable
 *
 * @package UserBundle\Datatables
 */
class UserDatatable extends BaseDatatable
{
    private $profile;

    public function getLineFormatter()
    {
        $formatter = function ($line) {
            if(empty($line['profile']['name'])) {
                $line['profile']['name'] = '<span class="text-muted">N/A</span>';
            }

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
        $this->ajax->set($this->getDefaultAjax(['url' => $this->router->generate('users_home')]));

        $this->options->set($this->getDefaultOptions([
            'individual_filtering' => true,
            'order' => array(array(1, 'asc')),
            'order_cells_top' => true,
        ]));
        $this->setDefaultExportButtons([1,2,3]);

        $this->addActionButton('user_update', 'Edit', 'glyphicon-edit', ['id' => 'id']);

//        if ($this->twig->isDebug() && $this->authorizationChecker->isGranted('ROLE_SUPER_ADMIN')) {
//            $this->addActionButton(
//                'homepage',
//                'Switch To',
//                'glyphicon-random',
//                ['_switch_user' => 'username'],
//                function ($row) {
//                    return isset($row['profile']['id']);
//                }
//            );
//        }

        $this->columnBuilder
            ->add('profile.photo', ProfileImageColumn::class, array(
                'title' => 'Photo',
                'width' => '62px',
                'holder_width' => '50',
                'relative_path' => 'uploads/serviceman/'
            ))
            ->add('username', Column::class, array(
                'title' => 'Username'
            ))
            ->add('email', Column::class, array(
                'title' => 'Email',
            ))
            ->add('groups.name', Column::class, array(
                'title' => 'Role',
                'data' => 'groups[, ].name'
            ))
            ->add('profile.name', Column::class, array(
                'title' => 'Personnel',
                'default_content' => ''
            ))
            ->add('enabled', BooleanColumn::class, array(
                'title' => 'Enabled',
                'searchable' => true,
                'orderable' => true,
                'true_label' => 'Yes',
                'false_label' => 'No',
                'true_icon' => 'glyphicon glyphicon-ok',
                'false_icon' => 'glyphicon glyphicon-remove',

            ))
        ;

        $this->initActionButtons();
    }



    /**
     * {@inheritdoc}
     */
    public function getEntity()
    {
        return 'UserBundle\Entity\User';
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'user_datatable';
    }
}
