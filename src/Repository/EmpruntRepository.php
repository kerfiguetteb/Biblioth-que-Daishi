<?php

namespace App\Repository;

use App\Entity\Emprunt;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Emprunt|null find($id, $lockMode = null, $lockVersion = null)
 * @method Emprunt|null findOneBy(array $criteria, array $orderBy = null)
 * @method Emprunt[]    findAll()
 * @method Emprunt[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EmpruntRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Emprunt::class);
    }

    /**
     * @return Emprunt[] Returns an array of Emprunt objects
     */
    
    public function findByEmprunteur($value)
    {
        return $this->createQueryBuilder('E')
            ->innerJoin('E.emprunteur', 'e')
            ->andWhere('e.id = :value')
            ->setParameter('value', $value)
            ->getQuery()
            ->getResult()
        ;
    }
    
    public function findByLivre($value)
    {
        return $this->createQueryBuilder('l')
            ->innerJoin('l.livre', 'e')
            ->andWhere('e.id = :value')
            ->setParameter('value', $value)
            ->getQuery()
            ->getResult()
        ;
    }

    public function findByDateRetour($value)
    {
        // Récupération d'un query builder.
        
        return $this->createQueryBuilder('e')
            ->andWhere('e.date_retour < :value')
            ->setParameter('value', $value)
            // ->orderBy('e.date_creation', 'ASC')
            // Récupération d'une requête qui n'attend qu'à être exécutée.
            ->getQuery()
            // Exécution de la requête.
            // Récupération d'un tableau de résultat.
            // Ce tableau peut contenir, zéro, un ou plusieurs lignes.
            ->getResult()
        ;
    }
    public function findByDateRetourNull($value)
    {
        // Récupération d'un query builder.
        
        return $this->createQueryBuilder('e')
            ->andWhere('e.date_retour = :value')
            ->setParameter('value', $value)
            // ->orderBy('e.date_creation', 'ASC')
            // Récupération d'une requête qui n'attend qu'à être exécutée.
            ->getQuery()
            // Exécution de la requête.
            // Récupération d'un tableau de résultat.
            // Ce tableau peut contenir, zéro, un ou plusieurs lignes.
            ->getResult()
        ;
    }


    // public function findByExampleField($value)
    // {
    //     return $this->createQueryBuilder('e')
    //         ->andWhere('e.exampleField = :val')
    //         ->setParameter('val', $value)
    //         ->orderBy('e.id', 'ASC')
    //         ->setMaxResults(10)
    //         ->getQuery()
    //         ->getResult()
    //     ;
    // }
    

    /*
    public function findOneBySomeField($value): ?Emprunt
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
