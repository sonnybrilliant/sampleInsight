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
namespace AppBundle\Form\Type\Advert;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AdvertCreateType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('title', TextType::class, array(
            'label' => 'Title',
            'attr' => array(
                'class' => 'form-control',
                'tabindex' => 1,
                'data-parsley-errors-container' => '#parsleyName',
                'data-parsley-required-message' => 'Title is required',
                'placeholder' => 'Advert title'
            ),
            'help' => 'Advert title',
            'parsley_error_container' => 'parsleyName'
        ))
            ->add('advertisingOrganization', EntityType::class, array(
                'label' => 'Organization',
                'class' => 'AppBundle\Entity\AdvertisingOrganization',
                'choice_label' => 'name',
                'placeholder' => '-- select organization --',
                'multiple' => false,
                'attr' => array(
                    'class' => 'select2',
                    'tabindex' => 2,
                    'data-parsley-errors-container' => '#parsleyOrg',
                    'data-parsley-required-message' => 'Organization is required.',
                ),
                'help' => 'Select the advertising organization',
                'parsley_error_container' => 'parsleyOrg',
            ))
            ->add('expireAt', DateType::class, array(
                'label' => 'Expiry date',
                'widget' => 'single_text',
                'attr' => array(
                    'class' => 'form-control',
                    'tabindex' => 3,
                    'data-parsley-errors-container' => '#parsleyExpiryDate',
                    'data-parsley-required-message' => 'Expiry date is required.',
                ),
                'date_icon' => 'Expiry date',
                'parsley_error_container' => 'parsleyExpiryDate',
                'required' => false
            ))

            ->add('localFile', FileType::class, array(
                    'label' => 'Audio file(MP3 format)',
                    'attr' => array(
                        'class' => 'form-control',
                        'tabindex' => 4,
                        'data-parsley-errors-container' => '#parsleyAudioFile',
                        'data-parsley-required-message' => 'Audio file is required.',
                    ),
                'parsley_error_container' => 'parsleyAudioFile',
            ))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Advert',
            'validation_groups' => array('create')
        ));
    }

    public function getName()
    {
        return 'app_bundle_advert_create_type';
    }
}
