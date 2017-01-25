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
namespace AppBundle\Form\Type\User\Compiler;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CompilerCreateType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        $builder->add('radioStation', EntityType::class, array(
            'label' => 'Radio station',
            'class' => 'AppBundle\Entity\RadioStation',
            'multiple' => false,
            'placeholder' => '-- select radio station --',
            'attr' => array(
                'class' => 'select2 ',
                'tabindex' => 1,
                'data-parsley-errors-container' => '#parsleyRadioStation',
                'data-parsley-required-message' => 'Radio station is required',
                'data-parsley-trigger' => 'change',
            ),
            'parsley_error_container' => 'parsleyRadioStation'
        ))
        ->add('firstName', TextType::class, array(
            'label' => 'First name',
            'attr' => array(
                'class' => 'form-control',
                'tabindex' => 1,
                'data-parsley-errors-container' => '#parsleyFirstName',
                'data-parsley-required-message' => 'First name is required',
                'placeholder' => 'First name'
            ),
            'help' => 'First name',
            'parsley_error_container' => 'parsleyFirstName'
        ))
            ->add('lastName', TextType::class, array(
                'label' => 'Surname',
                'attr' => array(
                    'class' => 'form-control',
                    'tabindex' => 2,
                    'data-parsley-errors-container' => '#parsleyLastName',
                    'data-parsley-required-message' => 'Surname is required',
                    'placeholder' => 'Surname'
                ),
                'help' => 'Surname',
                'parsley_error_container' => 'parsleyLastName'
            ))
            ->add('gender', EntityType::class, array(
                'label' => 'Gender',
                'class' => 'AppBundle\Entity\Gender',
                'choice_label' => 'title',
                'placeholder' => '-- select gender --',
                'multiple' => false,
                'attr' => array(
                    'class' => 'select2 ',
                    'tabindex' => 3,
                    'data-parsley-errors-container' => '#parsleyGender',
                    'data-parsley-required-message' => 'Gender is required',
                    'data-parsley-trigger' => 'change',
                ),
                'parsley_error_container' => 'parsleyGender'
            ))

            ->add('msisdn', TextType::class, array(
                'label' => 'Cellphone',
                'attr' => array(
                    'class' => 'form-control',
                    'tabindex' => 4,
                    'data-parsley-errors-container' => '#parsleyMsisdn',
                    'data-parsley-required-message' => 'Cellphone number is required',
                    'placeholder' => 'Cellphone',
                    'data-parsley-trigger' => 'change',
                ),
                'help' => 'Cellphone',
                'parsley_error_container' => 'parsleyMsisdn'
            ))

            ->add('email', EmailType::class, array(
                'label' => 'Email address',
                'attr' => array(
                    'class' => 'form-control',
                    'tabindex' => 5,
                    'data-parsley-errors-container' => '#parsleyEmailAddress',
                    'data-parsley-required-message' => 'Email address is required.',
                    'data-parsley-type' => 'email',
                    'data-parsley-trigger' => 'change',
                ),
                'help' => 'User email address',
                'parsley_error_container' => 'parsleyEmailAddress',
            ))

            ;

    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\User',
            'validation_groups' => array('create')
        ));
    }

    public function getName()
    {
        return 'app_bundle_compiler_create_type';
    }
}
