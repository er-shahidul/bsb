<?php

namespace AppBundle\Controller;

use AppBundle\Datatables\AuditLogDatatable;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

/**
 * @Route("/audit-log")
 */
class AuditLogController extends BaseController
{
    /**
     * @Route("", name="audit_log_list")
     * @param Request $request
     * @return \Sg\DatatablesBundle\Datatable\AbstractDatatable|\Symfony\Component\HttpFoundation\Response
     */
    public function indexAction(Request $request)
    {
        $datatable = $this->prepareDatatable(AuditLogDatatable::class, $request->isXmlHttpRequest());

        if ($request->isXmlHttpRequest()) {
            return $datatable;
        }

        return $this->render('AppBundle:AuditLog:index.html.twig', array(
            'datatable' => $datatable,
            'pageTitle' => 'Audit Log',
        ));
    }
}
