<?php

namespace UserBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

class GroupForm extends AbstractType
{
    private $class;
    /** @var  \UserBundle\Permission\PermissionBuilder */
    private $permissionBuilder;

    public function __construct($class, $permissionBuilder)
    {
        $this->class = $class;
        $this->permissionBuilder = $permissionBuilder;
    }
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('name', null, array(
            'label' => 'Name',
            'attr'  => array('class' => 'span5'),
            'constraints' => array(
                new NotBlank(array('message'=>'Name should not be blank'))
            ),
            'required' => false
        ));

        $builder->add('description', TextareaType::class, array(
            'label'    => 'Description',
            'required' => false,
            'attr'     => array('class' => 'span5', 'rows' => 3)
        ));

        $builder->add('roles', ChoiceType::class, array(
            'choices'  => $this->permissionBuilder->getPermissionHierarchyForChoiceField(),
            'multiple' => true,
            'constraints' => array(
                new NotBlank(array('message'=>'Roles should not be blank'))
            ),
            'required' => false
        ));

        $builder
            ->add('submit', SubmitType::class, array(
                'attr'     => array('class' => 'btn green')
            ))
        ;
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => $this->class,
            'intention'  => 'group',
        ));
    }

    /**
     * @return string
     */
    public function getBlockPrefix()
    {
        return 'fos_user_group';
    }
}
