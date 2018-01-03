<?php

namespace PersonnelBundle\Form;

use Doctrine\ORM\EntityRepository;
use PersonnelBundle\Entity\District;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\PropertyAccess\PropertyAccess;

trait InheritUpazilaModifierTrait
{
    /**
     * @param FormBuilderInterface $builder
     */
    protected function modifyUpazilaElements(FormBuilderInterface $builder)
    {
        $addUpazila = function (FormInterface $form, $district, $fieldName) {
            $queryBuilder = null;
            $choices = [];

            if (!empty($district)) {
                $choices = null;
                $queryBuilder = function (EntityRepository $er) use ($district) {
                    $qb = $er->createQueryBuilder('upazila');

                    if ($district instanceof District) {
                        $qb = $qb->where('upazila.district = :district')
                            ->setParameter('district', $district);
                    } else {
                        if (is_numeric($district)) {
                            $qb = $qb
                                ->innerJoin('upazila.district', 'district')
                                ->where('district.id = :id')
                                ->setParameter('id', $district);
                        }
                    }

                    return $qb;
                };
            }

            self::addField($form, $fieldName, $choices, $queryBuilder);
        };

        self::addField($builder, 'inheritUpazila');
        self::addField($builder, 'inheritPermanentUpazila');

        $builder
            ->addEventListener(FormEvents::POST_SET_DATA, function (FormEvent $event) use ($addUpazila) {
                $data = $event->getData();
                $form = $event->getForm();

                if (NULL === $data) {
                    return;
                }

                $accessor = PropertyAccess::createPropertyAccessor();
                $district = $accessor->getValue($data, 'inheritDistrict');
                $permanentDistrict = $accessor->getValue($data, 'inheritPermanentDistrict');

                $addUpazila($form, $district, 'inheritUpazila');
                $addUpazila($form, $permanentDistrict, 'inheritPermanentUpazila');
            });

        // Below is used to load the car selectbox when brand is submitted
        $builder
            ->addEventListener(FormEvents::PRE_SUBMIT, function (FormEvent $event) use ($addUpazila) {
                $form = $event->getForm();
                $data = $event->getData();
                if (array_key_exists('inheritDistrict', $data)) {
                    $addUpazila($form, $data['inheritDistrict'], 'inheritUpazila');
                }

                if (array_key_exists('inheritPermanentDistrict', $data)) {
                    $addUpazila($form, $data['inheritPermanentDistrict'], 'inheritPermanentUpazila');
                }
            });
    }

    /**
     * @param FormInterface|FormBuilderInterface $builder
     * @param  string                            $name
     * @param array                              $choices
     * @param null                               $queryBuilder
     */
    public static function addField($builder, $name, $choices = [], $queryBuilder = NULL)
    {
        $builder->add($name, EntityType::class, [
            'required' => false,
            'placeholder' => 'উপজেলা',
            'class'   => 'PersonnelBundle\Entity\Upazila',
            'label'   => 'উপজেলা',
            'query_builder'   => $queryBuilder,
            'choices'   => $choices
        ]);
    }
}