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
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TimeType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class RadioShowCreateType extends AbstractType
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
            ->add('radioShowType', EntityType::class, array(
                'label' => 'Type',
                'class' => 'AppBundle\Entity\RadioShowType',
                'choice_label' => 'title',
                'placeholder' => '-- select type --',
                'multiple' => false,
                'attr' => array(
                    'class' => 'select2',
                    'tabindex' => 2,
                    'data-parsley-errors-container' => '#parsleyType',
                    'data-parsley-required-message' => 'Radio show type is required.',
                ),
                'help' => 'Select radio show type',
                'parsley_error_container' => '#parsleyType',
            ))
            ->add('radioStation', EntityType::class, array(
                'label' => 'Radio station',
                'class' => 'AppBundle\Entity\RadioStation',
                'choice_label' => 'name',
                'placeholder' => '-- select radio station --',
                'multiple' => false,
                'attr' => array(
                    'class' => 'select2',
                    'tabindex' => 3,
                    'data-parsley-errors-container' => '#parsleyStation',
                    'data-parsley-required-message' => 'Radio station is required.',
                ),
                'help' => 'Select radio station',
                'parsley_error_container' => '#parsleyStation',
            ))
            ->add('isCrossOver', CheckboxType::class, array(
                'label' => 'Is Crossover?',
                'attr' => array(
                    'class' => 'form-control',
                    'tabindex' => 4
                ),
                'helpCheck' => 'Does show time slot cross over to the next day'
            ))
            ->add('description', TextareaType::class, array(
                'label' => 'Description',
                'attr' => array(
                    'class' => 'form-control',
                    'tabindex' => 5,
                    'style' => 'height:150px;'
                ),
                'required' => false
            ))
            ->add('startTime', TimeType::class, array(
                'label' => 'Start time',
                'widget' => 'single_text',
                'attr' => array(
                    'class' => 'form-control',
                    'tabindex' => 6,
                    'data-parsley-errors-container' => '#parsleyStartTime',
                    'data-parsley-required-message' => 'Start time is required',
                    'placeholder' => 'Start time'
                ),
                'help' => 'Show start time',
                'parsley_error_container' => 'parsleyStartTime'
            ))
            ->add('endTime', TimeType::class, array(
                'label' => 'End time',
                'widget' => 'single_text',
                'attr' => array(
                    'class' => 'form-control',
                    'tabindex' => 7,
                    'data-parsley-errors-container' => '#parsleyEndTime',
                    'data-parsley-required-message' => 'End time is required',
                    'placeholder' => 'End time'
                ),
                'help' => 'Show end time',
                'parsley_error_container' => 'parsleyEndTime'
            ))
            ->add('playsMonday', CheckboxType::class, array(
                'label' => 'Monday',
                'attr' => array(
                    'class' => 'form-control',
                    'tabindex' => 8,
                )
            ))
            ->add('playsTuesday', CheckboxType::class, array(
                'label' => 'Tuesday',
                'attr' => array(
                    'class' => 'form-control',
                    'tabindex' => 9,
                )
            ))
            ->add('playsWednesday', CheckboxType::class, array(
                'label' => 'Wednesday',
                'attr' => array(
                    'class' => 'form-control',
                    'tabindex' => 10,
                )
            ))
            ->add('playsThursday', CheckboxType::class, array(
                'label' => 'Thursday',
                'attr' => array(
                    'class' => 'form-control',
                    'tabindex' => 11,
                )
            ))
            ->add('playsFriday', CheckboxType::class, array(
                'label' => 'Friday',
                'attr' => array(
                    'class' => 'form-control',
                    'tabindex' => 12,
                )
            ))
            ->add('playsSaturday', CheckboxType::class, array(
                'label' => 'Saturday',
                'attr' => array(
                    'class' => 'form-control',
                    'tabindex' => 13,
                )
            ))
            ->add('playsSunday', CheckboxType::class, array(
                'label' => 'Sunday',
                'attr' => array(
                    'class' => 'form-control',
                    'tabindex' => 14,
                )
            ))
            ->add('radioShowScheduleType', EntityType::class, array(
                'label' => 'Schedule',
                'class' => 'AppBundle\Entity\RadioShowScheduleType',
                'choice_label' => 'title',
                'placeholder' => '-- select type --',
                'multiple' => false,
                'attr' => array(
                    'class' => 'select2',
                    'tabindex' => 15,
                    'data-parsley-errors-container' => '#parsleyScheduleType',
                    'data-parsley-required-message' => 'Radio show schedule type is required.',
                ),
                'help' => 'Select show schedule',
                'parsley_error_container' => 'parsleyScheduleType',
            ))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\RadioShow',
            'validation_groups' => array('create')
        ));
    }

    public function getName()
    {
        return 'app_bundle_radio_show_create_type';
    }
}
