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
namespace AppBundle\Form\Type\RadioStation;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RadioStationSongRejectViewType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('message', TextareaType::class, array(
            'label' => 'Why ',
            'attr' => array(
                'class' => 'form-control textarea',
                'tabindex' => 1,
                'cols' => 5,
                "style" => "height:150px;"
            ),
            'disabled' => true
        ))
            ->add('rejectedBy', EntityType::class,array(
                'label' => 'Rejected By ',
                'class' => 'AppBundle\Entity\User',
                'choice_label' => 'firstName',
                'attr' => array(
                    'class' => 'select2',
                    'tabindex' => 5
                )
            ))
            ->add('rejectedAt', DateTimeType::class,array(
                'label' => 'Rejected At',
                'widget' => 'single_text',
                'format' => 'yyyy-MM-dd  HH:m:s a',
                'attr' => array(
                    'class' => 'form-control'
                ),
                'help' => 'When was user updated',
                'disabled' => true
            ))

       ;

    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\RadioStationQueue'
        ));
    }

    public function getName()
    {
        return 'app_bundle_record_station_song_reject_type';
    }
}
