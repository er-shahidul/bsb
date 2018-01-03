<?php

namespace AppBundle\Form\Type\Extension;


use AppBundle\Entity\AttachmentsTrait;
use AppBundle\Subscriber\AddAttachmentsFieldSubscriber;
use Symfony\Component\Form\AbstractTypeExtension;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AttachmentTypeExtension extends AbstractTypeExtension
{

    /**
     * Adds a CSRF field to the form when the CSRF protection is enabled.
     *
     * @param FormBuilderInterface $builder The form builder
     * @param array                $options The options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        if (!$options['add_attachment']) {
            return;
        }

        if (empty($options['data_class'])) {
            return;
        }

        if (!AttachmentsTrait::hasAttachmentTrait($options['data_class'])) {
            return;
        }

        $builder->addEventSubscriber(new AddAttachmentsFieldSubscriber());
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'add_attachment' => TRUE
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getExtendedType()
    {
        return FormType::class;
    }
}