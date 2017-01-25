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
namespace AppBundle\Form\Type\Slogan;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SloganCreateType extends AbstractType
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
                'placeholder' => 'Slogan title'
            ),
            'help' => 'Slogan title',
            'parsley_error_container' => 'parsleyName'
        ))
            ->add('radioStation', EntityType::class, array(
                'label' => 'Radio station',
                'class' => 'AppBundle\Entity\RadioStation',
                'choice_label' => 'name',
                'placeholder' => '-- select radio station --',
                'multiple' => false,
                'attr' => array(
                    'class' => 'select2',
                    'tabindex' => 2,
                    'data-parsley-errors-container' => '#parsleyStation',
                    'data-parsley-required-message' => 'Radio station is required.',
                ),
                'help' => 'Select radio station',
                'parsley_error_container' => '#parsleyStation',
            ))

            ->add('localFile', FileType::class, array(
                    'label' => 'Audio file(MP3 format)',
                    'attr' => array(
                        'class' => 'form-control',
                        'tabindex' => 3,
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
            'data_class' => 'AppBundle\Entity\Slogan',
            'validation_groups' => array('create')
        ));
    }

    public function getName()
    {
        return 'app_bundle_slogan_create_type';
    }
}
