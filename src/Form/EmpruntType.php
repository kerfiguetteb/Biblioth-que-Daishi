<?php

namespace App\Form;

use App\Entity\Emprunt;
use App\Entity\Livre;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EmpruntType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('date_emprunt')
            ->add('date_retour')
            ->add('livre', EntityType::class,
            [
                'class' => Livre::class,
            // Le label qui est affiché utilisera le nom de la school year
            'choice_label' => function(Livre $livre) {
                return "{$livre->getTitre()}";
            },
            // Les school years sont triés par ordre croissant (c-à-d alphabétique) du champ name
            'query_builder' => function (EntityRepository $er) {
                return $er->createQueryBuilder('l')
                        ->orderBy('l.titre', 'ASC')
                    ;
                },
            ])

            ->add('emprunteur')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Emprunt::class,
        ]);
    }
}
