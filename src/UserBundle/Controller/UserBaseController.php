<?php

namespace UserBundle\Controller;

use AppBundle\Controller\BaseController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class UserBaseController extends BaseController
{
    protected function isOfficeAdmin($office = null)
    {
        $hasOfficeAdminRole = $this->isGranted('ROLE_OFFICE_ADMIN');

        if (!$office) {
            return $hasOfficeAdminRole;
        }

        return $hasOfficeAdminRole && $office == $this->getOffice();
    }
}
