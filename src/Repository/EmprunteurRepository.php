<?php

namespace App\Repository;

use App\Entity\Emprunteur;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Emprunteur|null find($id, $lockMode = null, $lockVersion = null)
 * @method Emprunteur|null findOneBy(array $criteria, array $orderBy = null)
 * @method Emprunteur[]    findAll()
 * @method Emprunteur[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EmprunteurRepository extends ServiceEntityRepository
{
    use ProfileRepositoryTrait;

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Emprunteur::class);
    }

    /**
     * @return Emprunteur[] Returns an array of Emprunteur objects
     */
    
    public function findOneByUser(User $user)
    {
        return $this->createQueryBuilder('e')
            ->innerJoin('e.user','u')    
            ->andWhere('e.user = :user')
            ->setParameter('user', $user)
            ->orderBy('e.id', 'ASC')
            // ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    
    public function findByFirstnameOrLastname($value)
    {
        // Récupération d'un query builder.
        $qb = $this->createQueryBuilder('e');

        return $qb->where($qb->expr()->orX(
                $qb->expr()->like('e.nom', ':value'),
                $qb->expr()->like('e.prenom', ':value')
            ))
            ->setParameter('value', "%{$value}%")
            ->orderBy('e.nom', 'ASC')
            ->orderBy('e.prenom', 'ASC')
            // Récupération d'une requête qui n'attend qu'à être exécutée.
            ->getQuery()
            // Exécution de la requête.
            // Récupération d'un tableau de résultat.
            // Ce tableau peut contenir, zéro, un ou plusieurs lignes.
            ->getResult()
        ;
    }

    public function findByTel($value)
    {
        // Récupération d'un query builder.
        $qb = $this->createQueryBuilder('e');

        return $qb->where($qb->expr()->orX(
                $qb->expr()->like('e.tel', ':value'),
            ))
            ->setParameter('value', "%{$value}%")
            ->orderBy('e.tel', 'ASC')
            // Récupération d'une requête qui n'attend qu'à être exécutée.
            ->getQuery()
            // Exécution de la requête.
            // Récupération d'un tableau de résultat.
            // Ce tableau peut contenir, zéro, un ou plusieurs lignes.
            ->getResult()
        ;
    }
    public function findByDateCreation($value)
    {
        // Récupération d'un query builder.
        
        return $this->createQueryBuilder('e')
            ->andWhere('e.date_creation < :value')
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
    public function findByActif($value)
    {
        // Récupération d'un query builder.
        
        return $this->createQueryBuilder('e')
            ->andWhere('e.actif = :value')
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


    /*
    public function findOneBySomeField($value): ?Emprunteur
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
