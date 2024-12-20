<?php

namespace App\Repository;

use App\Entity\CompetencesListe;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<CompetencesListe>
 *
 * @method CompetencesListe|null find($id, $lockMode = null, $lockVersion = null)
 * @method CompetencesListe|null findOneBy(array $criteria, array $orderBy = null)
 * @method CompetencesListe[]    findAll()
 * @method CompetencesListe[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CompetencesListeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CompetencesListe::class);
    }

    //    /**
    //     * @return CompetencesListe[] Returns an array of CompetencesListe objects
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

    //    public function findOneBySomeField($value): ?CompetencesListe
    //    {
    //        return $this->createQueryBuilder('c')
    //            ->andWhere('c.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
