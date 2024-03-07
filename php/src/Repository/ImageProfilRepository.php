<?php

namespace App\Repository;

use App\Entity\ImageProfil;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<ImageProfil>
 *
 * @method ImageProfil|null find($id, $lockMode = null, $lockVersion = null)
 * @method ImageProfil|null findOneBy(array $criteria, array $orderBy = null)
 * @method ImageProfil[]    findAll()
 * @method ImageProfil[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ImageProfilRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ImageProfil::class);
    }

//    /**
//     * @return ImageProfil[] Returns an array of ImageProfil objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('i')
//            ->andWhere('i.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('i.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?ImageProfil
//    {
//        return $this->createQueryBuilder('i')
//            ->andWhere('i.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
