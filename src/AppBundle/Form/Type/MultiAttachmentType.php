<?php

namespace AppBundle\Form\Type;


use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MultiAttachmentType extends AbstractType
{

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $entryOptionsNormalizer = function (Options $options, $value) {
            $value['block_name'] = 'entry';
            $value['data_class'] = $options->offsetGet('entry_data_class');

            return $value;
        };

        $resolver->setDefaults(array(
            'entry_data_class' => NULL,
            'allow_add' => TRUE,
            'label' => 'Attachments',
            'allow_delete' => TRUE,
            'by_reference' => false,
            'prototype' => true,
            'prototype_data' => null,
            'prototype_name' => '__name__',
            'entry_type' => AttachmentType::class,
            'entry_options' => array(),
            'delete_empty' => false,
        ));

        $resolver->setNormalizer('entry_options', $entryOptionsNormalizer);
    }

    public function getParent()
    {
        return CollectionType::class;
    }

}