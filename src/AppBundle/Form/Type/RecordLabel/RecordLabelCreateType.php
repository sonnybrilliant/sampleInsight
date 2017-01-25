<?php

/*
 * This file is part of the a Mlankatech (PTY) LTD Project.
 *
 * (c) Mfana Ronald Conco <ronald.conco@mlankatech.co.za>
 *
 * For the full copyright and license information, please view the LICENSE.
 *
 * Created At: 2016/10/01
 */
namespace AppBundle\Form\Type\RecordLabel;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\CountryType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RecordLabelCreateType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('name', TextType::class, array(
            'label' => 'Name',
            'attr' => array(
                'class' => 'form-control',
                'tabindex' => 1,
                'data-parsley-errors-container' => '#parsleyName',
                'data-parsley-required-message' => 'Name is required',
                'placeholder' => 'Record label'
            ),
            'help' => 'Record label name',
            'parsley_error_container' => 'parsleyName'
        ))
            ->add('registeredAs', TextType::class, array(
                'label' => 'Registered As',
                'attr' => array(
                    'class' => 'form-control',
                    'tabindex' => 2,
                    'placeholder' => 'Registered As?'
                ),
                'help' => 'Record label registered As?',
                'required' => false

            ))
            ->add('email', EmailType::class, array(
                'label' => 'Email address',
                'attr' => array(
                    'class' => 'form-control',
                    'tabindex' => 3,
                    'placeholder' => 'Email address'
                ),
                'help' => 'Email Address',
                'required' => false
            ))
            ->add('contactNumber', TextType::class, array(
                'label' => 'Contact number',
                'attr' => array(
                    'class' => 'form-control',
                    'tabindex' => 4,
                    'placeholder' => 'Contact number'
                ),
                'help' => 'Contact number',
                'required' => false
            ))
            ->add('summary', TextareaType::class, array(
                'label' => 'Short summary',
                'attr' => array(
                    'class' => 'form-control',
                    'tabindex' => 5,
                ),
                'help' => 'Short summary about record label',
                'required' => false
            ))
            ->add('country', CountryType::class, array(
                'label' => 'Country',
                'attr' => array(
                    'class' => 'form-control',
                    'tabindex' => 6,
                ),
                'help' => 'Country of origin',
                'required' => false
            ))
            ->add('isLocal', CheckboxType::class, array(
                'label' => 'Is Local?',
                'attr' => array(
                    'class' => 'form-control',
                    'tabindex' => 7
                )
            ))
            ->add('isAfrican', CheckboxType::class, array(
                'label' => 'Is African?',
                'attr' => array(
                    'class' => 'form-control',
                    'tabindex' => 8
                )
            ))
            ->add('website', TextType::class, array(
                'label' => 'Website',
                'attr' => array(
                    'class' => 'form-control',
                    'tabindex' => 9,
                    'placeholder' => 'Website'
                ),
                'help' => 'Record label website',
                'required' => false
            ))
            ->add('twitter', TextType::class, array(
                'label' => 'Twitter@ ',
                'attr' => array(
                    'class' => 'form-control',
                    'tabindex' => 10,
                    'placeholder' => 'Twitter'
                ),
                'help' => 'Record label twitter page',
                'required' => false
            ))
            ->add('facebook', TextType::class, array(
                'label' => 'Facebook page',
                'attr' => array(
                    'class' => 'form-control',
                    'tabindex' => 11,
                    'placeholder' => 'Facebook page'
                ),
                'help' => 'Record label facebook page',
                'required' => false
            ));
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\RecordLabel',
            'validation_groups' => array('create')
        ));
    }

    public function getName()
    {
        return 'app_bundle_record_label_create_type';
    }
}
