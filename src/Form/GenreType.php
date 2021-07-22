<?php

namespace App\Form;

use App\Entity\Genre;
use App\Entity\Livre;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class GenreType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nom')
            ->add('description')
            // ->add('livres', EntityType::class,
            // [
            //     'class' => Genre::class,
            // 'choice_label' => function(Genre $genre) {
            //     return "{$genre->getNom()}";
            // },
            // 'query_builder' => function (EntityRepository $er) {
            //     return $er->createQueryBuilder('g')
            //             ->orderBy('g.nom', 'ASC')
            //         ;
            //     },
            // ])

      ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Genre::class,
        ]);
    }
}
