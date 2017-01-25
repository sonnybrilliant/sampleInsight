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
namespace AppBundle\Form\Type\AdvertisingOrganization;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class AdvertisingOrganizationEditType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('name', TextType::class, array(
            'label' => 'Organization name',
            'attr' => array(
                'class' => 'form-control',
                'tabindex' => 1,
                'data-parsley-errors-container' => '#parsleyName',
                'data-parsley-required-message' => 'Name is required',
                'placeholder' => 'Advertising organization name'
            ),
            'help' => 'Name of the organization',
            'parsley_error_container' => 'parsleyName'
        ))
            ->add('industry', EntityType::class, array(
                'label' => 'Industry',
                'class' => 'AppBundle\Entity\Industry',
                'placeholder' => '-- select industry --',
                'choice_label' => 'title',
                'attr' => array(
                    'class' => 'select2',
                    'tabindex' => 2
                )
            ))
            ->add('contact', TextType::class, array(
                'label' => 'Contact person',
                'attr' => array(
                    'class' => 'form-control',
                    'tabindex' => 3,
                    'placeholder' => 'Contact',
                ),
                'help' => 'Organization contact person',
                'required' => false,
            ))
            ->add('email', EmailType::class, array(
                'label' => 'Email',
                'attr' => array(
                    'class' => 'form-control',
                    'tabindex' => 4,
                    'placeholder' => 'Email address',
                    'data-parsley-errors-container' => '#parsleyEmail',
                ),
                'help' => 'Organization email address',
                'parsley_error_container' => 'parsleyEmail',
                'required' => false
            ))
            ->add('telephone', TextType::class, array(
                'label' => 'Telephone',
                'attr' => array(
                    'class' => 'form-control',
                    'tabindex' => 5,
                    'placeholder' => 'Telephone',
                ),
                'help' => 'Organization telephone',
                'required' => false,
            ));

    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\AdvertisingOrganization',
            'validation_groups' => array('create')
        ));
    }

    public function getName()
    {
        return 'app_bundle_advert_organization_edit_type';
    }
}
