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
namespace AppBundle\Form\Type\Song;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SongCreateType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('title', TextType::class, array(
            'label' => 'Title',
            'attr' => array(
                'class' => 'form-control',
                'tabindex' => 1,
                'data-parsley-errors-container' => '#parsleyName',
                'data-parsley-required-message' => 'Name is required',
                'placeholder' => 'Song title'
            ),
            'help' => 'Song title',
            'parsley_error_container' => 'parsleyName'
        ))
            ->add('isrc', TextType::class, array(
                'label' => 'ISRC',
                'attr' => array(
                    'class' => 'form-control',
                    'tabindex' => 2,
                    'placeholder' => 'ISRC'
                ),
                'help' => 'The International Standard Recording Code (ISRC) is an international standard code for uniquely identifying sound recordings and music video recordings',
                'required' => false

            ))
            ->add('upc', TextType::class, array(
                'label' => 'UPC',
                'attr' => array(
                    'class' => 'form-control',
                    'tabindex' => 3,
                    'placeholder' => 'UPC'
                ),
                'help' => 'A UPC (Universal Product Code or Barcode) represents the entire digital product, as opposed to just an individual digital track',
                'required' => false
            ))
            ->add('album', TextType::class, array(
                'label' => 'Album',
                'attr' => array(
                    'class' => 'form-control',
                    'tabindex' => 4,
                    'placeholder' => 'Album'
                ),
                'help' => 'Album',
                'required' => false
            ))
            ->add('genres', EntityType::class, array(
                'label' => 'Genre',
                'class' => 'AppBundle\Entity\Genre',
                'choice_label' => 'title',
                'placeholder' => '-- select genre --',
                'multiple' => true,
                'attr' => array(
                    'class' => 'select2',
                    'tabindex' => 5,
                ),
                'help' => 'You can select multiple genre',
            ))
            ->add('recordLabel', EntityType::class, array(
                'label' => 'Record label',
                'class' => 'AppBundle\Entity\RecordLabel',
                'choice_label' => 'name',
                'placeholder' => '-- select record label --',
                'attr' => array(
                    'class' => 'select2',
                    'tabindex' => 6,
                    'data-parsley-errors-container' => '#parsleyRecordLabel',
                    'data-parsley-required-message' => 'Record label is required',
                ),
                'parsley_error_container' => 'parsleyRecordLabel'
            ))
            ->add('isCopyWritten', CheckboxType::class, array(
                'label' => 'Is Copy written?',
                'attr' => array(
                    'class' => '',
                    'tabindex' => 7
                ),
                'helpCheck' => 'Has this song been copy written already by a royalty agency?'
            ))
            ->add('localFile', FileType::class, array(
                    'label' => 'Audio file(MP3 format)',
                    'attr' => array(
                        'class' => 'form-control',
                        'tabindex' => 8
                    )
            ))
            ->add('artist', EntityType::class, array(
                'label' => 'Artist',
                'class' => 'AppBundle\Entity\Artist',
                'choice_label' => 'title',
                'placeholder' => '-- select artist --',
                'attr' => array(
                    'class' => 'select2',
                    'tabindex' => 9,
                    'data-parsley-errors-container' => '#parsleyArtist',
                    'data-parsley-required-message' => 'Artist is required',
                ),
                'help' => 'Main artist performing on the song',
                'parsley_error_container' => 'parsleyArtist'
            ))
            ->add('featuredArtist', TextType::class, array(
                'label' => 'Featured Artist/s',
                'attr' => array(
                    'class' => 'form-control',
                    'tabindex' => 10,
                    'placeholder' => 'Featured artist/s'
                ),
                'help' => 'Which artist is featured in the song',
                'required' => false
            ))
            ->add('targetedRadioStations', EntityType::class, array(
                'label' => 'Targeted Radio stations',
                'class' => 'AppBundle\Entity\RadioStation',
                'choice_label' => 'name',
                'placeholder' => '-- select radio stations --',
                'multiple' => true,
                'attr' => array(
                    'class' => 'select2',
                    'tabindex' => 11,
                ),
                'help' => 'Which radio stations are you targeting?',
                'required' => true
            ))
//            ->add('royaltyAgency', EntityType::class, array(
//                'label' => 'Royalty agency',
//                'class' => 'AppBundle\Entity\RoyaltyAgency',
//                'choice_label' => 'name',
//                'placeholder' => '-- select royalty agency --',
//                'attr' => array(
//                    'class' => 'select2',
//                    'tabindex' => 12,
//                ),
//                'help' => 'Select royalty agency if applicable ?',
//                'required' => false
//            ))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Song',
            'validation_groups' => array('create')
        ));
    }

    public function getName()
    {
        return 'app_bundle_song_create_type';
    }
}
