<?php

namespace App\Repository;

use App\Entity\Logement;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
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
    public function findByCriteria($styleCriteria, $eventCriteria, $tagCriteria)
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
                ->andWhere('t.tag = :tagCriteria')
                ->setParameter('tagCriteria', $tagCriteria);
        }

        return $qb->getQuery()->execute();
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
