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

use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class AdvertisingOrganizationProfileType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('name', TextType::class, array(
            'label' => 'Organization name',
            'attr' => array(
                'class' => 'form-control',
                'tabindex' => 1,
                'disabled' => true
            ),
            'help' => 'Name of the organization',
        ))
            ->add('industry', EntityType::class, array(
                'label' => 'Industry',
                'class' => 'AppBundle\Entity\Industry',
                'placeholder' => '-- select industry --',
                'choice_label' => 'title',
                'attr' => array(
                    'class' => 'select2',
                    'tabindex' => 2,
                    'disabled' => true
                )
            ))
            ->add('contact', TextType::class, array(
                'label' => 'Contact person',
                'attr' => array(
                    'class' => 'form-control',
                    'tabindex' => 3,
                    'placeholder' => 'Contact',
                    'disabled' => true
                ),
                'help' => 'Organization contact person',
            ))
            ->add('email', EmailType::class, array(
                'label' => 'Email',
                'attr' => array(
                    'class' => 'form-control',
                    'tabindex' => 4,
                    'placeholder' => 'Email address',
                    'disabled' => true
                ),
                'help' => 'Organization email address',
            ))
            ->add('telephone', TextType::class, array(
                'label' => 'Telephone',
                'attr' => array(
                    'class' => 'form-control',
                    'tabindex' => 5,
                    'placeholder' => 'Telephone',
                    'disabled' => true
                ),
                'help' => 'Organization telephone',
            ))
            ->add('createdAt', DateTimeType::class, array(
                'label' => 'Created At',
                'widget' => 'single_text',
                'format' => 'yyyy-MM-dd  HH:m:s a',
                'attr' => array(
                    'class' => 'form-control',
                    'tabindex' => 6
                ),
                'disabled' => true
            ))
            ->add('updatedAt', DateTimeType::class, array(
                'label' => 'Updated At',
                'widget' => 'single_text',
                'format' => 'yyyy-MM-dd  HH:m:s a',
                'attr' => array(
                    'class' => 'form-control',
                    'tabindex' => 6
                ),
                'disabled' => true
            ))
            ->add('lastActiveAt', DateTimeType::class, array(
                'label' => 'Last advert activity At',
                'widget' => 'single_text',
                'format' => 'yyyy-MM-dd  HH:m:s a',
                'attr' => array(
                    'class' => 'form-control',
                    'tabindex' => 6
                ),
                'disabled' => true
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
        return 'app_bundle_advert_organization_profile_type';
    }
}
