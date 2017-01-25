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
namespace AppBundle\Form\Type\User;

use Symfony\Component\Form\AbstractType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserProfileType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('firstName',TextType::class,array(
                    'label' => 'First name',
                    'attr' => array(
                        'class' => 'form-control'
                    ),
                    'help' => 'First name',
                    'disabled' => true,
                ))
                ->add('lastName', TextType::class,array(
                    'label' => 'Surname',
                    'attr' => array(
                        'class' => 'form-control'
                    ),
                    'help' => 'Surname',
                    'disabled' => true,
                ))
                ->add('email', TextType::class,array(
                    'label' => 'Email',
                    'attr' => array(
                        'class' => 'form-control'
                    ),
                    'help' => 'Email',
                    'disabled' => true,
                ))
                ->add('msisdn', TextType::class,array(
                    'label' => 'Msisdn',
                    'attr' => array(
                        'class' => 'form-control'
                    ),
                    'help' => 'Cellphone',
                    'disabled' => true,
                ))
                ->add('gender', EntityType::class,array(
                    'label' => 'Gender',
                    'class' => 'AppBundle\Entity\Gender',
                    'attr' => array(
                        'class' => 'form-control'
                    ),
                    'help' => 'Gender',
                    'disabled' => true,
                ))
                ->add('userGroup', EntityType::class,array(
                    'label' => 'Role',
                    'class' => 'AppBundle\Entity\UserGroup',
                    'attr' => array(
                        'class' => 'form-control'
                    ),
                    'help' => 'Role',
                    'disabled' => true,
                ))
                ->add('status', EntityType::class,array(
                    'label' => 'Status',
                    'class' => 'AppBundle\Entity\Status',
                    'attr' => array(
                        'class' => 'form-control'
                    ),
                    'help' => 'Status',
                    'disabled' => true
                ))
                ->add('createdAt', DateTimeType::class,array(
                    'label' => 'Created At',
                    'widget' => 'single_text',
                    'format' => 'yyyy-MM-dd  HH:m:s a',
                    'attr' => array(
                        'class' => 'form-control'
                    ),
                    'help' => 'When was user created',
                    'disabled' => true
                ))
                ->add('updatedAt', DateTimeType::class,array(
                    'label' => 'Updated At',
                    'widget' => 'single_text',
                    'format' => 'yyyy-MM-dd  HH:m:s a',
                    'attr' => array(
                        'class' => 'form-control'
                    ),
                    'help' => 'When was user updated',
                    'disabled' => true
                ))
                ->add('lastLoginAt', DateTimeType::class,array(
                    'label' => 'Last login At',
                    'widget' => 'single_text',
                    'format' => 'yyyy-MM-dd  HH:m:s a',
                    'attr' => array(
                        'class' => 'form-control'
                    ),
                    'help' => 'When was the last login',
                    'disabled' => true
                ));
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\User'
        ));
    }
}