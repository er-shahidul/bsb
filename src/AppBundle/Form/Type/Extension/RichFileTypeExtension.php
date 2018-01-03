<?php

namespace AppBundle\Form\Type\Extension;

use Symfony\Component\Form\AbstractTypeExtension;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\PropertyAccess\PropertyAccess;

class RichFileTypeExtension extends AbstractTypeExtension
{
    public function getExtendedType()
    {
        return FileType::class;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefined(array('name_property', 'url_property'));
    }

    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        if (isset($options['name_property'])) {
            $parentData = $form->getParent()->getData();

            $fileName = null;

            if (null !== $parentData) {
                $accessor = PropertyAccess::createPropertyAccessor();
                $fileName = $accessor->getValue($parentData, 'name');
            }

            $view->vars['file_name'] = $fileName;
        }

        if (isset($options['url_property'])) {
            $parentData = $form->getParent()->getData();

            $url = null;

            if (null !== $parentData) {
                $accessor = PropertyAccess::createPropertyAccessor();
                $url = $accessor->getValue($parentData, $options['url_property']);

                if (!file_exists($url)) {
                    $url = NULL;
                }
            }

            $view->vars['url'] = $url;
        }
    }
}