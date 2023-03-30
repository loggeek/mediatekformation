<?php
namespace App\Form;

use App\Entity\Categorie;
use App\Entity\Formation;
use App\Entity\Playlist;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class FormationForm extends AbstractType
{
    /**
     * Initialisation du formulaire.
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->add('title', TextType::class, [
                    'label' => 'Intitulé',
                    'required' => true,
                ])
                ->add('playlist', EntityType::class, [
                    'class' => Playlist::class,
                    'choice_label' => 'name',
                    'multiple' => false,
                    'required' => true,
                ])
                ->add('categories', EntityType::class, [
                    'class' => Categorie::class,
                    'label' => 'Catégorie',
                    'choice_label' => 'name',
                    'multiple' => true,
                    'required' => false,
                ])
                ->add('publishedAt', DateType::class, [
                    'widget' => 'single_text',
                    'data' => $options['data']->getPublishedAt(),
                    'label' => 'Date',
                    'required' => true,
                ])
                ->add('description', TextareaType::class, [
                    'label' => 'Description',
                    'required' => false,
                ])
                ->add('submit', SubmitType::class, [
                    'label' => 'Valider',
                ]);
    }
}
