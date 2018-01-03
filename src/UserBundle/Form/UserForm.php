<?php

namespace UserBundle\Form;

use AppBundle\Entity\Office;
use Doctrine\ORM\EntityRepository;
use MedicalBundle\Entity\Dispensary;
use MedicalBundle\Repository\DispensaryRepository;
use PersonnelBundle\Entity\ServingPersonnel;
use PersonnelBundle\Repository\ServingPersonnelRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\PropertyAccess\PropertyAccess;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use UserBundle\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class UserForm extends AbstractType
{
    /** @var User */
    protected $loginUser = null;

    /** @var AuthorizationCheckerInterface */
    protected $authorizationChecker;

    public function __construct($user, AuthorizationCheckerInterface $authorizationCheckerInterface)
    {
        $this->authorizationChecker = $authorizationCheckerInterface;
    }

    public function setLoginUser(User $user)
    {
        $this->loginUser = $user;
    }
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        $loginUser = $this->loginUser;
        /** @var User $data */
        $data = $options['data'];
        $attr = $options['attr'];
        $renderOffice = $attr['renderOffice'];

        if(empty($data->getId())) {
            $builder
                ->add(
                    'username',
                    null,
                    array(
                        'required'              => false,
                        'label'              => 'form.username',
                        'translation_domain' => 'FOSUserBundle',
                        'constraints'        => array(
                            new NotBlank(
                                array(
                                    'message' => 'Username should not be blank',
                                )
                            ),
                        ),
                    )
                );
        }

        $builder->add(
            'email',
            EmailType::class,
            array(
                'required'           => false,
                'label'              => 'form.email',
                'translation_domain' => 'FOSUserBundle',
                'constraints'        => array(
                    new NotBlank(
                        array(
                            'message' => 'Email should not be blank',
                        )
                    ),
                    new email(),
                ),
            )
        );

        $passwordConstrain = $data->getId() ? array() : array(
            new NotBlank(
                array(
                    'message' => 'Password should not be blank',
                )
            ),
            new Length(array('min' => 6)),
        );

        $builder->add(
            'plainPassword',
            RepeatedType::class,
            array(
                'type'            => PasswordType::class,
                'options'         => array('translation_domain' => 'FOSUserBundle'),
                'first_options'   => array('label' => 'form.password'),
                'second_options'  => array('label' => 'form.password_confirmation'),
                'invalid_message' => 'fos_user.password.mismatch',
                'constraints'     => $passwordConstrain,
                'required'        => false
            )
        );

        if(empty($data->getId())) {
            $builder->add('groups', EntityType::class, array(
                'class'         => 'UserBundle\Entity\Group',
                'query_builder' => function (EntityRepository $groupRepository) use ($loginUser) {
                    $qb = $groupRepository->createQueryBuilder('g')
                        ->andWhere("g.name != :group")->setParameter('group', 'Super Administrator');

                    if (!$this->authorizationChecker->isGranted('ROLE_ADMIN')) {
                        $qb->join('g.officeType', 'officeType');
                        $qb->andWhere("officeType.name = 'DASB'");
                    }

                    return $qb;
                },
                'choice_label'  => 'name',
                'multiple'      => TRUE,
                'label'         => 'Role',
                'required'      => FALSE
            ));
        }

        $builder->add('enabled');

        if ($renderOffice) {
            $builder->add('office', EntityType::class, [
                'required' => TRUE,
                'class'=> Office::class,
                'placeholder' => 'Select Office',
                'constraints'        => array(
                    new NotBlank(
                        array(
                            'message' => 'You must select an office',
                        )
                    ),
                ),
            ]);
        }

        $this->addPersonnel($builder);
        $this->addDispensary($builder);

        $builder->add('submit', SubmitType::class, array(
            'attr'     => array('class' => 'btn green')
        ));
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'UserBundle\Entity\User'
        ));
    }

    public function getBlockPrefix()
    {
        return 'user';
    }

    /**
     * @param FormBuilderInterface $builder
     */
    private function addPersonnel(FormBuilderInterface $builder)
    {
        $addProfile = function (FormInterface $form, $office) {
            $formOption = [
                'required'    => FALSE,
                'placeholder' => 'Select Personnel',
                'class'       => ServingPersonnel::class,
                'label'       => 'Select Personnel',
                'choices'     => []
            ];

            if (!empty($office)) {
                $formOption['choices'] = NULL;
                $formOption['query_builder'] = function (ServingPersonnelRepository $er) use ($office) {
                    $qb = $er->createQueryBuilder('sp');

                    $qb = $qb->where('sp.office = :office')
                        ->setParameter('office', $office);

                    return $qb;
                };
            }

            $form->add('profile', EntityType::class, $formOption);
        };

        $builder
            ->addEventListener(FormEvents::POST_SET_DATA, function (FormEvent $event) use ($addProfile) {
                $data = $event->getData();
                $form = $event->getForm();

                if (NULL === $data) {
                    return;
                }

                $accessor = PropertyAccess::createPropertyAccessor();
                $addProfile($form, $accessor->getValue($data, 'office'));

            });

        $builder
            ->addEventListener(FormEvents::PRE_SUBMIT, function (FormEvent $event) use ($addProfile) {
                $form = $event->getForm();
                $data = $event->getData();

                if (array_key_exists('office', $data)) {
                    $addProfile($form, $data['office']);
                }
            });
    }

    private function addDispensary(FormBuilderInterface $builder)
    {
        $addDispensary = function (FormInterface $form, $office) {
            $formOption = [
                'constraints'        => array(
                    new NotBlank(
                        array(
                            'message' => 'You must select a dispensary',
                        )
                    ),
                ),
                'placeholder' => 'Select Dispensary',
                'class'       => Dispensary::class,
                'label'       => 'Select Dispensary',
                'choices'     => []
            ];

            if (!empty($office)) {
                $formOption['choices'] = NULL;
                $formOption['query_builder'] = function (DispensaryRepository $er) use ($office) {
                    $qb = $er->createQueryBuilder('d');

                    $qb = $qb->where('d.office = :office')
                        ->setParameter('office', $office);

                    return $qb;
                };

                $form->add('dispensary', EntityType::class, $formOption);
            }
        };

        $builder
            ->addEventListener(FormEvents::POST_SET_DATA, function (FormEvent $event) use ($addDispensary) {
                $data = $event->getData();
                $form = $event->getForm();

                if (NULL === $data) {
                    return;
                }

                $accessor = PropertyAccess::createPropertyAccessor();
                $office = $accessor->getValue($data, 'office');
                $group = $accessor->getValue($data, 'groups');

                if(!empty($office) && !empty($group[0]) && $group[0]->getName()=== 'Dispensary Clerk') {
                    $addDispensary($form, $office);
                }
            });

        $builder
            ->addEventListener(FormEvents::PRE_SUBMIT, function (FormEvent $event) use ($addDispensary) {
                $form = $event->getForm();
                $data = $event->getData();

                if (array_key_exists('office', $data)) {
                    $addDispensary($form, $data['office']);
                }
            });
    }
}