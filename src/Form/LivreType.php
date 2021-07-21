<?php

namespace App\Form;

use App\Entity\Livre;
use App\Entity\Emprunt;
use App\Entity\Auteur;
use App\Entity\Genre;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class LivreType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('titre')
            ->add('annee_edition')
            ->add('nombre_pages')
            ->add('code_isbn')
            // ->add('auteur')
            // Déclaration d'un champ EntityType                           
            ->add('auteur', EntityType::class,
            [
                'class' => Auteur::class,
            // Le label qui est affiché utilisera le nom de la school year
            'choice_label' => function(Auteur $auteur) {
                return "{$auteur->getNom()}";
            },
            // Les school years sont triés par ordre croissant (c-à-d alphabétique) du champ name
            'query_builder' => function (EntityRepository $er) {
                return $er->createQueryBuilder('a')
                        ->orderBy('a.nom', 'ASC')
                    ;
                },
            ])
            // ->add('emprunts', EntityType::class, [
            //     'class' => Emprunt::class,
            //     'choice_label' => function(User $emprunt) {
            //         return "{$emprunt->getDateEmprunt()} {$emprunt->getDateRetour()}";
            //     },
            //     'by_reference' => false,
            //     'query_builder' => function (EntityRepository $er) {
            //         return $er->createQueryBuilder('e')
            //             ->orderBy('e.date_emprunt', 'ASC')
            //             ->orderBy('l.date_retour', 'ASC')
            //         ;
            //     },
            //     'multiple' => true,
            //     'by_reference' => false,
            // ])
        


            ->add('genres', EntityType::class,
            [
                'class' => Genre::class,
            // Le label qui est affiché utilisera le nom de la school year
            'choice_label' => function(Genre $genre) {
                return "{$genre->getNom()}";
            },
            'query_builder' => function (EntityRepository $er) {
                return $er->createQueryBuilder('g')
                        ->orderBy('g.nom', 'ASC')
                    ;
                },
            ])

      ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Livre::class,
        ]);
    }
}
