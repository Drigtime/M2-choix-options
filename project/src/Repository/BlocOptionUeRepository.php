<?php

namespace App\Repository;

use App\Entity\Main\BlocOptionUe;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<BlocOptionUe>
 *
 * @method BlocOptionUe|null find($id, $lockMode = null, $lockVersion = null)
 * @method BlocOptionUe|null findOneBy(array $criteria, array $orderBy = null)
 * @method BlocOptionUe[]    findAll()
 * @method BlocOptionUe[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BlocOptionUeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, BlocOptionUe::class);
    }

    public function save(BlocOptionUe $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(BlocOptionUe $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

//    /**
//     * @return BlocOptionUe[] Returns an array of BlocOptionUe objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('b')
//            ->andWhere('b.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('b.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?BlocOptionUe
//    {
//        return $this->createQueryBuilder('b')
//            ->andWhere('b.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
