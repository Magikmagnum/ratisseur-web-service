<?php

namespace App\Repository;

use App\Entity\ExperiencesListe;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<ExperiencesListe>
 *
 * @method ExperiencesListe|null find($id, $lockMode = null, $lockVersion = null)
 * @method ExperiencesListe|null findOneBy(array $criteria, array $orderBy = null)
 * @method ExperiencesListe[]    findAll()
 * @method ExperiencesListe[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ExperiencesListeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ExperiencesListe::class);
    }

//    /**
//     * @return ExperiencesListe[] Returns an array of ExperiencesListe objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('e')
//            ->andWhere('e.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('e.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?ExperiencesListe
//    {
//        return $this->createQueryBuilder('e')
//            ->andWhere('e.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
