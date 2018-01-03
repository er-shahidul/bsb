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

trait UpazilaModifierTrait
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

        self::addField($builder, 'upazila');
        self::addField($builder, 'permanentUpazila');

        $builder
            ->addEventListener(FormEvents::POST_SET_DATA, function (FormEvent $event) use ($addUpazila) {
                $data = $event->getData();
                $form = $event->getForm();

                if (NULL === $data) {
                    return;
                }

                $accessor = PropertyAccess::createPropertyAccessor();
                $district = $accessor->getValue($data, 'district');
                $permanentDistrict = $accessor->getValue($data, 'permanentDistrict');

                $addUpazila($form, $district, 'upazila');
                $addUpazila($form, $permanentDistrict, 'permanentUpazila');
            });

        // Below is used to load the car selectbox when brand is submitted
        $builder
            ->addEventListener(FormEvents::PRE_SUBMIT, function (FormEvent $event) use ($addUpazila) {
                $form = $event->getForm();
                $data = $event->getData();
                if (array_key_exists('district', $data)) {
                    $addUpazila($form, $data['district'], 'upazila');
                }

                if (array_key_exists('permanentDistrict', $data)) {
                    $addUpazila($form, $data['permanentDistrict'], 'permanentUpazila');
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
            'required' => TRUE,
            'placeholder' => 'উপজেলা',
            'class'   => 'PersonnelBundle\Entity\Upazila',
            'label'   => 'উপজেলা',
            'query_builder'   => $queryBuilder,
            'choices'   => $choices
        ]);
    }
}