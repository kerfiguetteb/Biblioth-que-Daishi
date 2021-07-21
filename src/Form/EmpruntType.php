<?php

namespace App\Form;

use App\Entity\Emprunt;
use App\Entity\Emprunteur;
use App\Entity\Livre;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
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
            'choice_label' => function(Livre $livre) {
                return "{$livre->getTitre()}";
            },
            'query_builder' => function (EntityRepository $er) {
                return $er->createQueryBuilder('l')
                        ->orderBy('l.titre', 'ASC')
                    ;
                },
            ])

            ->add('emprunteur', EntityType::class,
            [
                'class' => Emprunteur::class,
            'choice_label' => function(Emprunteur $emprunteur) {
                return "{$emprunteur->getNom()} {$emprunteur->getPrenom()}";
            },
            'query_builder' => function (EntityRepository $er) {
                return $er->createQueryBuilder('e')
                ->orderBy('e.nom', 'ASC')
                ->orderBy('e.prenom', 'ASC')
                ;
                },
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Emprunt::class,
        ]);
    }
}
