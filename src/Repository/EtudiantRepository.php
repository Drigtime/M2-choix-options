<?php

namespace App\Repository;

use App\Entity\Main\Etudiant;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Etudiant>
 *
 * @method Etudiant|null find($id, $lockMode = null, $lockVersion = null)
 * @method Etudiant|null findOneBy(array $criteria, array $orderBy = null)
 * @method Etudiant[]    findAll()
 * @method Etudiant[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EtudiantRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Etudiant::class);
    }

    public function save(Etudiant $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Etudiant $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

//    /**
//     * @return Etudiant[] Returns an array of Etudiant objects
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

    public function findOneById($value): ?Etudiant
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.id = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult();
    }

    public function findByFilters(array $filters): array
    {
        $qb = $this->createQueryBuilder('e')
            ->leftJoin('e.parcours', 'p')
        ;

        if (isset($filters['nom']) && !empty($filters['nom'])) {
            $qb->andWhere('e.nom LIKE :nom')
                ->setParameter('nom', '%' . $filters['nom'] . '%');
        }

        if (isset($filters['prenom']) && !empty($filters['prenom'])) {
            $qb->andWhere('e.prenom LIKE :prenom')
                ->setParameter('prenom', '%' . $filters['prenom'] . '%');
        }

        if (isset($filters['parcours']) && !empty($filters['parcours'])) {
            $qb->andWhere('p.id = :parcours')
                ->setParameter('parcours', $filters['parcours']);
        }

        return $qb->getQuery()->getResult();
    }
}
