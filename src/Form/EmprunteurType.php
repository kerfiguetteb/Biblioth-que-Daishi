<?php

namespace App\Form;

use App\Entity\Emprunteur;
use App\Entity\Emprunt;
use App\Entity\User;
use App\Form\UserType;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EmprunteurType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nom')
            ->add('prenom')
            ->add('tel')
            ->add('actif')
            ->add('date_creation')
            ->add('date_modification')
            ->add('user', UserType::class, [
                'label_attr' => [
                    'class' => 'd-none',
                ]
            ])
            ->add('emprunts', EntityType::class, [
                'class' => Emprunt::class,
                // La fonction anonyme doit renvoyer une chaîne de caractères
                // qui contient le texte qui sera utilisé dans le menu déroulant. 
                'choice_label' => function(Emprunt $emprunt) {
                    // On renvoit les attributs firstname et lastname.
                    // return "{$emprunt->getDateEmprunt()->format('m/Y')} {$emprunt->getDateRetour()->format('m/Y')}";
                },
                'multiple' => true,
                'expanded' => true,
            ])
        ;

        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Emprunteur::class,
        ]);
    }
}