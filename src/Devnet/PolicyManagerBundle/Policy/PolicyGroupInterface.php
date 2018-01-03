<?php

namespace Devnet\PolicyManagerBundle\Policy;

use Symfony\Component\Form\FormFactory;
use Symfony\Component\Form\FormInterface;

interface PolicyGroupInterface
{
    /**
     * @param FormFactory $formFactory
     * @param null $data
     * @return FormInterface
     */
    public function getForm(FormFactory $formFactory, $data = null);
    public static function getLabel();
    public static function getNameSpace();
}