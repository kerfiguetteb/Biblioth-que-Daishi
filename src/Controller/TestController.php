<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Livre;
use App\Entity\Empunteur;
use App\Entity\Emprunt;
use App\Entity\Auteur;
use App\Repository\LivreRepository;
use App\Repository\EmprunteurRepository;
use App\Repository\AuteurRepository;
use App\Repository\EmpruntRepository;
use App\Repository\GenreRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TestController extends AbstractController
{
    /**
     * @Route("/test", name="test")
     */
    public function index(
        LivreRepository $livreRepository,
        EmprunteurRepository $emprunteurRepository,
        EmpruntRepository $empruntRepository,
        AuteurRepository $auteurRepository,
        GenreRepository $genreRepository,
        UserRepository $userRepository): Response

    {
        $entityManager = $this->getDoctrine()->getManager();
       
        // - USER -
    //  la liste complète de tous les utilisateurs (de la table `user`)
        $user = $userRepository->findAll();
        // dump($user);

    // - les données de l'utilisateur dont l'id est `1`
        $user = $userRepository->find(1);
        dump($user);


    // - les données de l'utilisateur dont l'email est `foo.foo@example.com`
        $email = 'foo.foo@example.com';
        $users = $userRepository->findByEmail($email);
        dump($users);

    // - les données des utilisateurs dont l'attribut `roles` contient le mot clé `ROLE_EMRUNTEUR`
        $emprunteurRoles = $userRepository->findByRole('ROLE_EMPRUNTEUR');
        dump($emprunteurRoles);


        // - LIVRE -
    // - la liste complète de tous les livres
        $livre = $livreRepository->findAll();
        // dump($livre);

    // - les données du livre dont l'id est `1`
        $livre = $livreRepository->find(1);
        dump($livre);

    // - la liste des livres dont le titre contient le mot clé `lorem`
        $titre = 'lorem';
        $livres = $livreRepository->findByTitre($titre);
        dump($livres);

    // - la liste des livres dont l'id de l'auteur est `2`
        $livre = $livreRepository->findAll();
        $auteur = $auteurRepository->findAll();
        $auteur = $auteurRepository->find(2);
        $livre = $livreRepository->findByAuteur($auteur);
        dump($livre);

    // - la liste des livres dont le genre contient le mot clé `roman`
       
        $livre = $livreRepository->findAll();
        $genre = $genreRepository->findAll();
        $type = 'roman';
        $livre = $livreRepository->findByGenre($type);
        dump($livre);


        // - ajouter un nouveau livre
        $livre = new livre();
        
        // - titre : Totum autem id externum
        // $livre = setTitre('Totum autem id externum');
        
        // - année d'édition : 2020
        $livre->setAnneeEdition(2020);
        
        // - nombre de pages : 300
        $livre->setNombrePages(300);
        
        // - code ISBN : 9790412882714
        $livre->setCodeIsbn('9790412882714');
        
        // - auteur : Hugues Cartier (id `2`)
        $auteur = $auteurRepository->findAll();
        $livre->setAuteur($auteur[1]);
        
        // - genre : science-fiction (id `6`)
        $genre = $genreRepository->findAll();
        $livre->addGenre($genre[5]);
       
        // $entityManager->flush();
        dump($livre);
   
    
        
        // - modifier le livre dont l'id est `2`
        // - titre : Aperiendum est igitur 
        // - genre : roman d'aventure (id `5`)
        
        $livreId2 = $livreRepository->findAll()[1];
        $livreId2->setTitre(' Aperiendum est igitur');
        
        $genres = $genreRepository->findAll();
        $genre = $genres[4]->setNom("roman d'aventure");
        $livreId2->addGenre($genre);

        dump($livreId2);

// Requêtes de suppression :
// - supprimer le livre dont l'id est `123`

    // if($livre)
    // {
    //             $livre = $livreRepository->find(123);
    //             $entityManager->remove($livre);
    //             $entityManager->flush();
    //         }

 ### Les emprunteurs

// Requêtes de lecture :



// - liste complète des emprunteurs
        $emprunteur = $emprunteurRepository->findAll();
        $user = $userRepository->findAll();
        $user = $userRepository->find(3);
        dump($user);
        // - les données de l'emprunteur qui est relié au user dont l'id est `3`
        $emprunteur = $emprunteurRepository->findOneByUser($user);
        dump($emprunteur);
        // - les données de l'emprunteur dont l'id est `3`
        $emprunteur = $emprunteurRepository->find(3);
        dump($emprunteur);

// - la liste des emprunteurs dont le nom ou le prénom contient le mot clé `foo`
        $emprunteur = $emprunteurRepository->findAll();
        $Name = 'foo';
        $emprunteur = $emprunteurRepository->findByFirstnameOrLastname($Name);
        dump($emprunteur);


// - la liste des emprunteurs dont le téléphone contient le mot clé `1234`
        $emprunteur = $emprunteurRepository->findAll();
        $tel = '1234';
        $emprunteur = $emprunteurRepository->findByTel($tel);
        dump($emprunteur);




// - la liste des emprunteurs dont la date de création est antérieure au 01/03/2021 exclu (c-à-d strictement plus petit)
        $emprunteur = $emprunteurRepository->findAll();
        $creation = '2021-03-01';
        $emprunteur = $emprunteurRepository->findByDateCreation($creation);
        dump($emprunteur);


// - la liste des emprunteurs inactifs (c-à-d dont l'attribut `actif` est égal à `false`)
        $actif = true;
        $emprunteur = $emprunteurRepository->findAll();
        $emprunteur = $emprunteurRepository->findByActif($actif);
        dump($emprunteur);


# Emprunt

    // - la liste des 10 derniers emprunts au niveau chronologique
        $emprunt = $empruntRepository->findAll();
        $emprunt = array_slice($emprunt,-10, 10);
        dump($emprunt);

    // - la liste des emprunts de l'emprunteur dont l'id est `2`
        $emprunt= $empruntRepository->findAll();
        $emprunteur = $emprunteurRepository->find(2);
        $emprunt = $empruntRepository->findByEmprunteur($emprunteur);
        dump($emprunt);

    // - la liste des emprunts du livre dont l'id est `3`
   
        $emprunt= $empruntRepository->findAll();
        $livre = $livreRepository->findAll();
        $livre = $livreRepository->find(3);
        $emprunt = $empruntRepository->findByLivre($livre);
        dump($emprunt);

    // - la liste des emprunts qui ont été retournés avant le 01/01/2021
        $emprunt= $empruntRepository->findAll();
        $retour = '2021-01-01';
        $emprunt = $empruntRepository->findByDateRetour($retour);
        dump($emprunt);


    // - la liste des emprunts qui n'ont pas encore été retournés (c-à-d dont la date de retour est nulle)
        $emprunt= $empruntRepository->findAll();
        dump($emprunt);

        $retour = null;
        $emprunt = $empruntRepository->findByDateRetourNull($retour);
        dump($emprunt);

    // - les données de l'emprunt du livre dont l'id est `3` et qui n'a pas encore été retournés (c-à-d dont la date de retour est nulle)

    //     Requêtes de création :
    
    // - ajouter un nouvel emprunt
        $emprunt= $empruntRepository->findAll();

        $emprunt = new Emprunt();
        
    // - date d'emprunt : 01/12/2020 à 16h00
        $emprunt->setDateEmprunt(\DateTime::createFromFormat('Y-m-d H:i:s', '2020-12-01 16:00:00'));
    
    // - date de retour : aucune date
    
    // - emprunteur : foo foo (id `1`)
        $emprunteur = $emprunteurRepository->findAll();
        $emprunteur=$emprunteur[0]->setNom('foo foo');
        $emprunt->setEmprunteur($emprunteur);               
    
    
    // - livre : Lorem ipsum dolor sit amet (id `1`)
        $livre = $livreRepository->findAll();
        $livre[1] = 'Lorem ipsum dolor sit amet';
        $emprunt->setLivre($livre[0]);                  
        dump($emprunt);


    // Requêtes de mise à jour :


    // - modifier l'emprunt dont l'id est `3`
    // - date de retour : 01/05/2020 à 10h00
//         $emprunt = $empruntRepository->findOneById(3);
//         $emprunt->setDateRetour(\DateTime::createFromFormat('Y-m-d H:i:s', '2020-05-01 10:00:00'));
//         $entityManager->persist($emprunt);
//         $entityManager->flush();
//         dump($emprunt);


//     // Requêtes de suppression :
//     // - supprimer l'emprunt dont l'id est `42`
   
// if($emprunt)
//     {
//         $emprunt = $empruntRepository->find(42);
//         $entityManager->remove($emprunt);
//         $entityManager->flush();
//     }


        exit();

    }
}
