<?php

namespace App\Repository;

use App\Entity\Competences;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Competences>
 *
 * @method Competences|null find($id, $lockMode = null, $lockVersion = null)
 * @method Competences|null findOneBy(array $criteria, array $orderBy = null)
 * @method Competences[]    findAll()
 * @method Competences[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CompetencesRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Competences::class);
    }

    public function save(Competences $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Competences $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function findAllExcepteUser(User $user)
    {
        // Créer une requête pour récupérer toutes les compétences sauf celles associées à cet utilisateur
        return $this->createQueryBuilder('c')
            ->where('c.user != :user')
            ->setParameter('user', $user)
            ->getQuery()
            ->getResult();
    }

    //    /**
    //     * @return Competences[] Returns an array of Competences objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('c')
    //            ->andWhere('c.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('c.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Competences
    //    {
    //        return $this->createQueryBuilder('c')
    //            ->andWhere('c.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
