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
namespace AppBundle\Form\Type\RadioShow;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RadioShowTypeCreateType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('title', TextType::class, array(
            'label' => 'Title',
            'attr' => array(
                'class' => 'form-control',
                'tabindex' => 1,
                'data-parsley-errors-container' => '#parsleyTitle',
                'data-parsley-required-message' => 'Title is required',
                'placeholder' => 'Title'
            ),
            'help' => 'Radio show type title',
            'parsley_error_container' => 'parsleyTitle'
        ))

        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\RadioShowType',
            'validation_groups' => array('create')
        ));
    }

    public function getName()
    {
        return 'app_bundle_radio_show_type_create_type';
    }
}
