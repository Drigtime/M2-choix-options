<?php

namespace App\Repository;

use App\Entity\UserGestion;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<UserGestion>
 *
 * @method UserGestion|null find($id, $lockMode = null, $lockVersion = null)
 * @method UserGestion|null findOneBy(array $criteria, array $orderBy = null)
 * @method UserGestion[]    findAll()
 * @method UserGestion[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserGestionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, UserGestion::class);
    }

    public function save(UserGestion $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(UserGestion $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

   /**
    * @return UserGestion[] Returns an array of UserGestion objects
    */
   public function findByExampleField($value): array
   {
       return $this->createQueryBuilder('u')
           ->andWhere('u.exampleField = :val')
           ->setParameter('val', $value)
           ->orderBy('u.id', 'ASC')
           ->setMaxResults(10)
           ->getQuery()
           ->getResult()
       ;
   }

   public function findOneBySomeField($value): ?UserGestion
   {
       return $this->createQueryBuilder('u')
           ->andWhere('u.exampleField = :val')
           ->setParameter('val', $value)
           ->getQuery()
           ->getOneOrNullResult()
       ;
   }
}
