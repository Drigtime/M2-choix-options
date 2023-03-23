<?php

namespace App\Repository;

use App\Entity\User\ResetPasswordToken;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<ResetPasswordToken>
 *
 * @method ResetPasswordToken|null find($id, $lockMode = null, $lockVersion = null)
 * @method ResetPasswordToken|null findOneBy(array $criteria, array $orderBy = null)
 * @method ResetPasswordToken[]    findAll()
 * @method ResetPasswordToken[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PasswordTokenRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ResetPasswordToken::class);
    }

    public function save(ResetPasswordToken $entity, bool $flush = false): void
    {
        $this->getEntityManager('user')->persist($entity);

        if ($flush) {
            $this->getEntityManager('user')->flush();
        }
    }

    public function remove(ResetPasswordToken $entity, bool $flush = false): void
    {
        $this->getEntityManager('user')->remove($entity);

        if ($flush) {
            $this->getEntityManager('user')->flush();
        }
    }

//    /**
//     * @return ResetPasswordToken[] Returns an array of ResetPasswordToken objects
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

//    public function findOneBySomeField($value): ?ResetPasswordToken
//    {
//        return $this->createQueryBuilder('u')
//            ->andWhere('u.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
