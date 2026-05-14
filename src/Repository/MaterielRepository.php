<?php

namespace App\Repository;

use App\Entity\Materiel;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Materiel>
 */
class MaterielRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Materiel::class);
    }

    //    /**
    //     * @return Materiel[] Returns an array of Materiel objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('m')
    //            ->andWhere('m.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('m.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Materiel
    //    {
    //        return $this->createQueryBuilder('m')
    //            ->andWhere('m.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
    public function countAvailable(): int
{
    return $this->createQueryBuilder('m')
        ->select('COUNT(m.id)')
        ->where('m.quantite > 0')
        ->getQuery()
        ->getSingleScalarResult();
}

public function countAvailableSearch(string $search): int
{
    $qb = $this->createQueryBuilder('m')
        ->select('COUNT(m.id)')
        ->where('m.quantite > 0');

    if ($search) {
        $qb->andWhere('m.nom LIKE :search')
           ->setParameter('search', '%' . $search . '%');
    }

    return $qb->getQuery()->getSingleScalarResult();
}

public function findAvailablePaginated(int $start, int $length, string $search = ''): array
{
    $qb = $this->createQueryBuilder('m')
        ->where('m.quantite > 0');

    if ($search) {
        $qb->andWhere('m.nom LIKE :search')
           ->setParameter('search', '%' . $search . '%');
    }

    return $qb->setFirstResult($start)
              ->setMaxResults($length)
              ->getQuery()
              ->getResult();
}
}
