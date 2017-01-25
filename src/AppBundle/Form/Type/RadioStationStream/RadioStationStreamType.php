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
namespace AppBundle\Form\Type\RadioStationStream;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RadioStationStreamType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('id', TextType::class, array(
            'label' => '#ID',
            'attr' => array(
                'class' => 'form-control',
                'tabindex' => 1
            ),
            'disabled' => true

        ))
            ->add('title', TextType::class, array(
                'label' => 'Song title',
                'attr' => array(
                    'class' => 'form-control',
                    'tabindex' => 2
                ),
                'disabled' => true
            ))
            ->add('isrc', TextType::class, array(
                'label' => 'ISRC',
                'attr' => array(
                    'class' => 'form-control',
                    'tabindex' => 3
                ),
                'disabled' => true
            ))
            ->add('artist', TextType::class, array(
                'label' => 'Artist',
                'attr' => array(
                    'class' => 'form-control',
                    'tabindex' => 4
                ),
                'disabled' => true
            ))
            ->add('album', TextType::class, array(
                'label' => 'Album',
                'attr' => array(
                    'class' => 'form-control',
                    'tabindex' => 5
                ),
                'disabled' => true
            ))
            ->add('label', TextType::class, array(
                'label' => 'Record label',
                'attr' => array(
                    'class' => 'form-control',
                    'tabindex' => 6
                ),
                'disabled' => true
            ))
            ->add('upc', TextType::class, array(
                'label' => 'UPC',
                'attr' => array(
                    'class' => 'form-control',
                    'tabindex' => 7
                ),
                'disabled' => true
            ))
            ->add('releaseAt', DateTimeType::class, array(
                'label' => 'Released At',
                'widget' => 'single_text',
                'format' => 'yyyy-MM-dd',
                'attr' => array(
                    'class' => 'form-control',
                    'tabindex' => 8
                ),
                'disabled' => true
            ))
            ->add('playedAt', DateTimeType::class, array(
                'label' => 'Played At',
                'widget' => 'single_text',
                'format' => 'yyyy-MM-dd  HH:m:s a',
                'attr' => array(
                    'class' => 'form-control',
                    'tabindex' => 9
                ),
                'disabled' => true
            ))
            ->add('radioStation', TextType::class, array(
                'label' => 'Radio station',
                'attr' => array(
                    'class' => 'form-control',
                    'tabindex' => 10
                ),
                'disabled' => true
            ))
            ->add('streamId', TextType::class, array(
                'label' => 'Stream ID',
                'attr' => array(
                    'class' => 'form-control',
                    'tabindex' => 11
                ),
                'disabled' => true
            ))
            ->add('version', TextType::class, array(
                'label' => 'Api version',
                'attr' => array(
                    'class' => 'form-control',
                    'tabindex' => 12
                ),
                'disabled' => true
            ))
            ->add('createdAt', DateTimeType::class, array(
                'label' => 'Captured At',
                'widget' => 'single_text',
                'format' => 'yyyy-MM-dd  HH:m:s a',
                'attr' => array(
                    'class' => 'form-control',
                    'tabindex' => 13
                ),
                'disabled' => true
            ))
         ;

    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\RadioStationStream',
            'validation_groups' => array('view')
        ));
    }

    public function getName()
    {
        return 'app_bundle_radio_station_stream_view_type';
    }
}
