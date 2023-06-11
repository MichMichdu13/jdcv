<?php

namespace App\Repository;

use App\Entity\Logement;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Logement>
 *
 * @method Logement|null find($id, $lockMode = null, $lockVersion = null)
 * @method Logement|null findOneBy(array $criteria, array $orderBy = null)
 * @method Logement[]    findAll()
 * @method Logement[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class LogementRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Logement::class);
    }

    public function save(Logement $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Logement $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }
    public function findByCriteria($styleCriteria = null, $eventCriteria = null, array $tagCriteria = null, \DateTime $startDate = null, \DateTime $endDate = null, string $department= null, $page = 1, $limit = 20)
    {
        $qb = $this->createQueryBuilder('l');

        if ($styleCriteria !== null) {
            $qb->leftJoin('l.style', 'stl')
                ->leftJoin('stl.style', 's')
                ->andWhere('s.name = :styleCriteria')
                ->setParameter('styleCriteria', $styleCriteria);
        }

        if ($eventCriteria !== null) {
            $qb->leftJoin('l.event', 'etl')
                ->leftJoin('etl.event', 'e')
                ->andWhere('e.name = :eventCriteria')
                ->setParameter('eventCriteria', $eventCriteria);
        }

        if ($tagCriteria !== null) {
            $qb->leftJoin('l.tags', 'ttl')
                ->leftJoin('ttl.tag', 't')
                ->andWhere('t.tag IN (:tagCriteria)')
                ->setParameter('tagCriteria', $tagCriteria);
        }
        if ($startDate !== null && $endDate !== null) {
            $qb->leftJoin('l.reservations', 'r')
                ->andWhere('r.date >= :startDate AND r.date <= :endDate OR r.user IS NULL')
                ->setParameter('startDate', $startDate)
                ->setParameter('endDate', $endDate);
        }

        if ($department !== null) {
            $qb->andWhere('SUBSTRING(l.cp, 1, 2) = :department')
                ->setParameter('department', $department);
        }
        $qb->setFirstResult(($page-1) * $limit)
            ->setMaxResults($limit);

        $paginator = new Paginator($qb);

        return $paginator;
    }


//    /**
//     * @return Logement[] Returns an array of Logement objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('l')
//            ->andWhere('l.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('l.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Logement
//    {
//        return $this->createQueryBuilder('l')
//            ->andWhere('l.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
