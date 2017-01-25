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
namespace AppBundle\Form\Type\Artist;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ArtistEditType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('isBand', CheckboxType::class, array(
                'label' => 'Is a Group/Collaboration?',
                'attr' => array(
                    'class' => 'form-control',
                    'tabindex' => 1
                ),
                'helpCheck' => 'Solo act or Group ?'
            ))
            ->add('status', EntityType::class, array(
                'label' => 'Status',
                'class' => 'AppBundle\Entity\Status',
                'choice_label' => 'title',
                'placeholder' => '-- select status --',
                'multiple' => false,
                'attr' => array(
                    'class' => 'select2',
                    'tabindex' => 2
                )
            ))
            ->add('title', TextType::class, array(
                'label' => 'Act name',
                'attr' => array(
                    'class' => 'form-control',
                    'tabindex' => 3,
                    'data-parsley-errors-container' => '#parsleyName',
                    'data-parsley-required-message' => 'Name is required',
                    'placeholder' => 'Artist / Act name'
                ),
                'help' => 'Name of the act',
                'parsley_error_container' => 'parsleyName'
            ))

            ->add('fullName', TextType::class, array(
                'label' => 'Full name',
                'attr' => array(
                    'class' => 'form-control soloAct',
                    'tabindex' => 4,
                    'placeholder' => 'Artist full name'
                ),
                'help' => 'Artist full name',
                'required' => false
            ))

            ->add('gender', EntityType::class, array(
                'label' => 'Gender',
                'class' => 'AppBundle\Entity\Gender',
                'choice_label' => 'title',
                'placeholder' => '-- select gender --',
                'multiple' => false,
                'attr' => array(
                    'class' => 'select2 soloAct',
                    'tabindex' => 5
                ),
                'required' => false
            ))
            ->add('isLocal', CheckboxType::class, array(
                'label' => 'Is local?',
                'attr' => array(
                    'class' => 'form-control',
                    'tabindex' => 6
                ),
                'helpCheck' => 'Is the act South African'
            ))
            ->add('isAfrican', CheckboxType::class, array(
                'label' => 'Is Africa?',
                'attr' => array(
                    'class' => 'form-control',
                    'tabindex' => 7
                ),
                'helpCheck' => "Is the act Africa, if you have chosen 'Is Local?', then ignore"
            ))
            ->add('genres', EntityType::class, array(
                'label' => 'Genres',
                'class' => 'AppBundle\Entity\Genre',
                'choice_label' => 'title',
                'multiple' => true,
                'attr' => array(
                    'class' => 'select2',
                    'tabindex' => 8
                ),
                'help' => 'You can select multiple genres?'
            ))
            ->add('artistImage', FileType::class,array(
                'label' => 'Profile image(Jpeg)',
                'data_class' => null,
                'attr' => array(
                    'class' => 'form-control',
                    'tabindex' => 9,
                ),
                'required' => false
            ))
            ->add('bio', TextareaType::class, array(
                'label' => 'Short bio',
                'attr' => array(
                    'class' => 'form-control',
                    'tabindex' => 9,
                    'style' => 'height:150px;'
                ),
                'required' => false
            ))
            ->add('bioSource', UrlType::class, array(
                'label' => 'Source',
                'attr' => array(
                    'class' => 'form-control',
                    'tabindex' => 10,
                    'placeholder' => 'Bio Source'
                ),
                'help' => 'Biography source ',
                'required' => false
            ))
            ->add('website', TextType::class, array(
                'label' => 'Website',
                'attr' => array(
                    'class' => 'form-control',
                    'tabindex' => 11,
                    'placeholder' => 'Website'
                ),
                'help' => 'Artist / Act website',
                'required' => false
            ))
            ->add('twitter', TextType::class, array(
                'label' => 'Twitter',
                'attr' => array(
                    'class' => 'form-control',
                    'tabindex' => 12,
                    'placeholder' => 'Twitter'
                ),
                'help' => 'Artist / Act twitter page',
                'required' => false
            ))
            ->add('facebook', TextType::class, array(
                'label' => 'Facebook page',
                'attr' => array(
                    'class' => 'form-control',
                    'tabindex' => 13,
                    'placeholder' => 'Facebook page'
                ),
                'help' => 'Artist / Act facebook page',
                'required' => false
            ))
            ->add('apiDeezerId', TextType::class, array(
                'label' => 'Deezer ID',
                'attr' => array(
                    'class' => 'form-control',
                    'tabindex' => 14,
                    'placeholder' => 'Deezer Id'
                ),
                'help' => 'Deezer API Id',
                'required' => false
            ));
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Artist',
            'validation_groups' => array('edit')
        ));
    }

    public function getName()
    {
        return 'app_bundle_artist_edit_type';
    }
}
