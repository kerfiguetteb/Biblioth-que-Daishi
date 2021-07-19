<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Livre;
use App\Entity\Genre;
use App\Entity\Auteur;
use App\Entity\Emprunteur;
use App\Entity\Emprunt;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Faker\Factory as FakerFactory;


class AppFixtures extends Fixture implements FixtureGroupInterface
{
    private $encoder;
    private $faker;

    public function __construct(UserPasswordEncoderInterface $encoder)
    {   
        $this->encoder = $encoder;
        $this->faker = FakerFactory::create('fr_FR');
    }
    
    public static function getGroups(): array
    {        
        return ['test'];
    }

    public function load(ObjectManager $manager)
    {
        // $product = new Product();
        // $manager->persist($product);
      
        $this->loadAdmins($manager);
        $genres = $this->loadGenres($manager);
        $auteurs = $this->loadAuteurs($manager,500);
        $livres = $this->loadLivres($manager, $genres, $auteurs, 1000);
        $emprunteurs = $this->loadEmprunteurs($manager, 100);
        $emprunts = $this->loadEmprunts($manager, $livres,$emprunteurs, 200);

        $manager->flush();
    }

    public function loadAdmins(ObjectManager $manager)
    {
        $user = new User();
        $user->setEmail('admin@example.com');
        // Hachage du mot de passe.
        $password = $this->encoder->encodePassword($user, '123');
        $user->setPassword($password);
        $user->setRoles(['ROLE_ADMIN']);

        $manager->persist($user);

    }


    public function loadGenres(ObjectManager $manager)
    {
        $genres = [];

        $tab = array(
            array('nom' => 'poésie'),
            array('nom' => 'nouvelle'),
            array('nom' => 'roman historique'),
            array('nom' => "roman d'amour"),
            array('nom' => "roman d'aventure"),
            array('nom' => "science-fiction"),
            array('nom' => 'fantasy'),
            array('nom' => 'biographie'),
            array('nom' => 'conte'),
            array('nom' => 'témoignage'),
            array('nom' => 'théâthre'),
            array('nom' => 'essai'),
            array('nom' => 'journal intime'),

        );            
        foreach($tab as $row)
        {
          // On crée la catégorie
          $genre = new Genre();
          $genre->setNom($row['nom']);
        
          // On la persiste
          $manager->persist($genre);
          $genres[] = $genre;
        } 
        return $genres;
    }

    public function loadAuteurs(ObjectManager $manager, int $count)
    {
        $auteurs = [];

        $auteur = new Auteur();
        $auteur->setNom('');
        $auteur->setPrenom('');
        $manager->persist($auteur); 
        $auteurs[] = $auteur;
       
       
        $auteur = new Auteur();
        $auteur->setNom('Cartier');
        $auteur->setPrenom('Hugues');
        $manager->persist($auteur);  
        $auteurs[] = $auteur;
      
       
        $auteur = new Auteur();
        $auteur->setNom('Lambert');
        $auteur->setPrenom('Armand');
        $manager->persist($auteur); 
        $auteurs[] = $auteur;
       
       
        $auteur = new Auteur();
        $auteur->setNom('Moitessier');
        $auteur->setPrenom('Thomas');
        $manager->persist($auteur);        
        $auteurs[] = $auteur;

        for ($i = 4; $i < $count; $i++) {
    
            $auteur = new Auteur();
            $auteur->setNom($this->faker->Lastname());
            $auteur->setPrenom($this->faker->Firstname());
            $manager->persist($auteur);
            $auteurs[] = $auteur;     
        }    
        return $auteurs;

    }
    public function loadLivres(ObjectManager $manager, array $genres, array $auteurs, int $count)
    {

        $livres = [];
        $auteurIndex = 0;
        $auteur = $auteurs[$auteurIndex];

        $livre = new Livre();
        $livre->setTitre('Lorem ipsum dolor sit amet');
        $livre->setAnneeEdition(2010);
        $livre->setNombrePages(100);
        $livre->setCodeIsbn('9785786930024');
        $livre->setAuteur($auteur);
        $manager->persist($livre);
        $livres[] = $livre;

        
        $auteurIndex = 1;
        $auteur = $auteurs[$auteurIndex];

        $livre = new Livre();
        $livre->setTitre('Consectetur adipiscing elit');
        $livre->setAnneeEdition(2011);
        $livre->setNombrePages(150);
        $livre->setCodeIsbn('9783817260935');
        $livre->setAuteur($auteur);
        $manager->persist($livre);
        $livres[] = $livre;


        $auteurIndex = 2;
        $auteur = $auteurs[$auteurIndex];

        $livre = new Livre();
        $livre->setTitre('Mihi quidem Antiochum');
        $livre->setAnneeEdition(2010);
        $livre->setNombrePages(110);
        $livre->setCodeIsbn('9782020493727');
        $livre->setAuteur($auteur);
        $manager->persist($livre);
        $livres[] = $livre;

       
        $auteurIndex = 3;
        $auteur = $auteurs[$auteurIndex];

        $livre = new Livre();
        $livre->setTitre('uem audis satis belle ');
        $livre->setAnneeEdition(2010);
        $livre->setNombrePages(110);
        $livre->setCodeIsbn('9794059561353');
        $livre->setAuteur($auteur);
        $manager->persist($livre);
        $livres[] = $livre;
        
        
        for ($i = 4; $i < $count; $i++) {

            $auteur = $auteurs[$auteurIndex];

            $livre = new Livre();
            $livre->setTitre($this->faker->sentence(4));
            $livre->setAnneeEdition($this->faker->year($max = 'now'));
            $livre->setNombrePages((rand(100,250)));
            $livre->setAuteur($auteur);
            
            $genresCount = random_int(0, 2);
            $randomGenres = $this->faker->randomElements($genres, $genresCount);

            $auteursCount = random_int(0, 2);
            $randomAuteurs = $this->faker->randomElements($auteurs, $genresCount);

            
            foreach ($randomGenres as $randomGenre) {
                $livre->addGenre($randomGenre);
            }   
            foreach ($randomAuteurs as $randomAuteur) {
                $livre->setAuteur($randomAuteur);
            }   
                        
            $manager->persist($livre);
            $livres[] = $livre;
            
        }    
        return $livres;
    }
    public function loadEmprunteurs(ObjectManager $manager,  int $count)
    {
        $emprunteurs = [];

        $user = new User();
        $user->setEmail('foo.foo@example.com');
        // Hachage du mot de passe.
        $password = $this->encoder->encodePassword($user, '123');
        $user->setPassword($password);
        $user->setRoles(['ROLE_EMPRUNTEUR']);
        $manager->persist($user);

        $emprunteur = new Emprunteur();
        $emprunteur->setNom('foo');
        $emprunteur->setPrenom('foo');
        $emprunteur->setTel('123456789');
        $emprunteur->setActif(true);
        $emprunteur->setDateCreation(\DateTime::createFromFormat('Y-m-d H:i:s', '2010-01-01 10:00:00'));
        $emprunteur->setUser($user);
        $manager->persist($emprunteur);
        $emprunteurs[] = $emprunteur;

        $user = new User();
        $user->setEmail('bar.bar@example.com');
        // Hachage du mot de passe.
        $password = $this->encoder->encodePassword($user, '123');
        $user->setPassword($password);
        $user->setRoles(['ROLE_EMPRUNTEUR']);
        $manager->persist($user);
        $emprunteur = new Emprunteur();
        $emprunteur->setNom('bar');
        $emprunteur->setPrenom('bar');
        $emprunteur->setTel('123456789');
        $emprunteur->setActif(false);
        $emprunteur->setDateCreation(\DateTime::createFromFormat('Y-m-d H:i:s', '2010-02-01 10:00:00'));
        $emprunteur->setDateModification(\DateTime::createFromFormat('Y-m-d H:i:s', '2020-05-01 12:00:00'));
        $emprunteur->setUser($user);
        $manager->persist($emprunteur);
        $emprunteurs[] = $emprunteur;

        $user = new User();
        $user->setEmail('baz.baz@example.com');
        // Hachage du mot de passe.
        $password = $this->encoder->encodePassword($user, '123');
        $user->setPassword($password);
        $user->setRoles(['ROLE_EMPRUNTEUR']);
        $manager->persist($user);
        $emprunteur = new Emprunteur();
        $emprunteur->setNom('baz');
        $emprunteur->setPrenom('baz');
        $emprunteur->setTel('123456789');
        $emprunteur->setActif(true);
        $emprunteur->setDateCreation(\DateTime::createFromFormat('Y-m-d H:i:s', '2020-03-01 12:00:00'));
        $emprunteur->setUser($user);

        $manager->persist($emprunteur);

        $emprunteurs[] = $emprunteur;

        for ($i = 3; $i < $count; $i++) {

        $user = new User();
        $user->setEmail($this->faker->email());
        // Hachage du mot de passe.
        $password = $this->encoder->encodePassword($user, '123');
        $user->setPassword($password);
        $user->setRoles(['ROLE_EMPRUNTEUR']);

        $manager->persist($user);

        $emprunteur = new Emprunteur();
        $emprunteur->setNom($this->faker->Lastname());
        $emprunteur->setPrenom($this->faker->Firstname());
        $emprunteur->setTel($this->faker->e164PhoneNumber());
        $emprunteur->setActif($this->faker-> boolean );
        $emprunteur->setDateCreation($this->faker->dateTimeThisDecade()); 
        $emprunteur->setUser($user);

        $emprunteurs[] = $emprunteur;
        $manager->persist($emprunteur);   
        }  

        return $emprunteurs;
    }


    
    public function loadEmprunts(ObjectManager $manager,array $livres, array $emprunteurs, int $count)
    {
        $emprunts = [];

        $emprunteurIndex = 0;
        $emprunteur = $emprunteurs[$emprunteurIndex];        
        $livreIndex = 0;
        $livre = $livres[$livreIndex];

        $emprunt = new Emprunt();
        $emprunt->setDateEmprunt(\DateTime::createFromFormat('Y-m-d H:i:s', '2020-02-01 10:00:00'));
        $date_emprunt = $emprunt->getDateEmprunt();
        $date_retour = \DateTime::createFromFormat('Y-m-d H:i:s', $date_emprunt->format('Y-m-d H:i:s'));
        $date_retour->add(new \DateInterval('P1M'));
        $emprunt->setDateRetour($date_retour);
        $emprunt->setEmprunteur($emprunteur);               
        $emprunt->setLivre($livre);                  
        $manager->persist($emprunt);
        $emprunts[] = $emprunt;


        $emprunteurIndex = 1;
        $emprunteur = $emprunteurs[$emprunteurIndex];        
        $livreIndex = 1;
        $livre = $livres[$livreIndex];

        $emprunt = new Emprunt();
        $emprunt->setDateEmprunt(\DateTime::createFromFormat('Y-m-d H:i:s', '2020-03-01 10:00:00'));
        $date_emprunt = $emprunt->getDateEmprunt();
        // $date_retour = \DateTime::createFromFormat('Y-m-d H:i:s', $date_emprunt->format('Y-m-d H:i:s'));
        // $date_retour->add(new \DateInterval('P1M'));
        // $emprunt->setDateRetour($date_retour);
        $emprunt->setEmprunteur($emprunteur);               
        $emprunt->setLivre($livre);               
        $manager->persist($emprunt);
        $emprunts[] = $emprunt;


        $emprunteurIndex = 2;
        $emprunteur = $emprunteurs[$emprunteurIndex];        
        $livreIndex = 2;
        $livre = $livres[$livreIndex];

        $emprunt = new Emprunt();
        $emprunt->setDateEmprunt(\DateTime::createFromFormat('Y-m-d H:i:s', '2010-01-01 00:00:00'));
        $date_emprunt = $emprunt->getDateEmprunt();
        $emprunt->setDateRetour($date_retour);
        $emprunt->setEmprunteur($emprunteur);               
        $emprunt->setLivre($livre);               
        $manager->persist($emprunt);
        $emprunts[] = $emprunt;
        
        for ($i = 3; $i < $count; $i++) {

            $livre = $livres[$livreIndex];

            $emprunteur = $emprunteurs[$emprunteurIndex];
        
            $emprunt = new Emprunt();
            $emprunt->setDateEmprunt($this->faker->dateTimeThisDecade());
            $date_emprunt = $emprunt->getDateEmprunt();
            $date_retour = \DateTime::createFromFormat('Y-m-d H:i:s', $date_emprunt->format('Y-m-d H:i:s'));
            $date_retour->add(new \DateInterval('P1M'));
            $emprunt->setDateRetour($date_retour);
            $emprunt->setEmprunteur($emprunteur); 
            $emprunt->setLivre($livre);               
              
    
            $livresCount = random_int(0, 5);
            $randomLivres = $this->faker->randomElements($livres, $livresCount);

            $emprunteursCount = random_int(0, 5);
            $randomEmprunteurs = $this->faker->randomElements($emprunteurs, $emprunteursCount);


            foreach ($randomLivres as $randomLivre) {
                $emprunt->setLivre($randomLivre);
            } 
            foreach ($randomEmprunteurs as $randomEmprunteur) {
                $emprunt->setEmprunteur($randomEmprunteur); 
            } 
            
            $manager->persist($emprunt);
            $emprunts[] = $emprunt;
        }  
        return $emprunts;
    }


    

}
