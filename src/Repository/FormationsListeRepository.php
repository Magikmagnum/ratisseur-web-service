<?php

namespace App\Repository;

use App\Entity\FormationsListe;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<FormationsListe>
 *
 * @method FormationsListe|null find($id, $lockMode = null, $lockVersion = null)
 * @method FormationsListe|null findOneBy(array $criteria, array $orderBy = null)
 * @method FormationsListe[]    findAll()
 * @method FormationsListe[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FormationsListeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, FormationsListe::class);
    }

//    /**
//     * @return FormationsListe[] Returns an array of FormationsListe objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('f')
//            ->andWhere('f.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('f.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?FormationsListe
//    {
//        return $this->createQueryBuilder('f')
//            ->andWhere('f.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
