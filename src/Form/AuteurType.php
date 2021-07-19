<?php

namespace App\Form;

use App\Entity\Auteur;
use App\Entity\Livre;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AuteurType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nom')
            ->add('prenom')
            ->add('livres', EntityType::class, [
                'class' => Livre::class,
                'choice_label' => function(User $livre) {
                    return "{$livre->getNom()} {$livre->getPrenom()}";
                },
                'by_reference' => false,
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('l')
                        ->orderBy('l.nom', 'ASC')
                        ->orderBy('l.prenom', 'ASC')
                    ;
                },
                'multiple' => true,
                'by_reference' => false,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Auteur::class,
        ]);
    }
}
