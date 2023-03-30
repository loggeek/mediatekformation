<?php

namespace App\Form;

use App\Entity\Formation;
use App\Entity\Playlist;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PlaylistFormModif extends AbstractType
{
    /**
     * Initialisation du formulaire.
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->add('name', TextType::class, [
                    'label' => 'Intitulé de la playlist',
                    'required' => true,
                ])
                ->add('formations', EntityType::class, [
                    'class' => Formation::class,
                    'label' => 'Formation(s)',
                    'choice_label' => 'title',
                    'multiple' => true,
                    'disabled' => true,
                ])

                ->add('description', TextareaType::class, [
                    'label' => 'Description',
                    'required' => false,
                ])

                ->add('submit', SubmitType::class, [
                    'label' => 'Valider',
                ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Playlist::class,
        ]);
    }
}
