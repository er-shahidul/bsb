<?php

namespace AppBundle\Controller;

use AppBundle\Entity\FileAttachment;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\Response;

/**
 * @Route("/attachment")
 */
class FileController extends BaseController
{
    /**
     * @Route("/view/{id}/{fileName}", name="app_file_view")
     * @param FileAttachment $fileAttachment
     * @param                $fileName
     *
     * @return BinaryFileResponse
     */
    public function indexAction(FileAttachment $fileAttachment, $fileName)
    {
        $file = $this->getFilePath($fileAttachment, $fileName);

        return new BinaryFileResponse($file);
    }

    /**
     * @Route("/download/{id}/{fileName}", name="app_file_download", options={"expose"=true})
     * @param FileAttachment $fileAttachment
     * @param                $fileName
     *
     * @return Response
     */
    public function masterDataIndexAction(FileAttachment $fileAttachment, $fileName)
    {
        $file = $this->getFilePath($fileAttachment, $fileName);
        $response = new Response();
        $response->headers->set('Content-type', 'application/octet-stream');
        $response->headers->set('Content-Disposition', sprintf('attachment; filename="%s"', $fileName));
        $response->setContent(file_get_contents($file));

        return $response;
    }

    /**
     * @param FileAttachment $fileAttachment
     * @param                $fileName
     *
     * @return string
     */
    private function getFilePath(FileAttachment $fileAttachment, $fileName)
    {
        $file = $fileAttachment->getAbsolutePath();

        if (strtolower($fileAttachment->getName()) !== strtolower($fileName)) {
            throw $this->createNotFoundException('File not found');
        }

        if (!file_exists($file)) {
            throw $this->createNotFoundException('File not found');
        }

        return $file;
    }
}
