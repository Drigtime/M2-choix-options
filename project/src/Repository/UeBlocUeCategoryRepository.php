<?php

namespace App\Repository;

use App\Entity\UeBlocUeCategory;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<UeBlocUeCategory>
 *
 * @method UeBlocUeCategory|null find($id, $lockMode = null, $lockVersion = null)
 * @method UeBlocUeCategory|null findOneBy(array $criteria, array $orderBy = null)
 * @method UeBlocUeCategory[]    findAll()
 * @method UeBlocUeCategory[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UeBlocUeCategoryRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, UeBlocUeCategory::class);
    }

    public function save(UeBlocUeCategory $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(UeBlocUeCategory $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

//    /**
//     * @return UeBlocUeCategory[] Returns an array of UeBlocUeCategory objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('u')
//            ->andWhere('u.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('u.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?UeBlocUeCategory
//    {
//        return $this->createQueryBuilder('u')
//            ->andWhere('u.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
