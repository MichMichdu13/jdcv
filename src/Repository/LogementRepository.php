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
    public function findByCriteria($styleCriteria = null, $eventCriteria = null, array $tagCriteria = null, \DateTime $startDate = null, \DateTime $endDate = null, string $department= null,int $nbPersonne=null, $page = 1, $limit = 10)
    {
        $qb = $this->createQueryBuilder('l');

        if ($styleCriteria !== null) {
            $qb->leftJoin('l.style', 's')
                ->andWhere('s.name = :styleCriteria')
                ->setParameter('styleCriteria', $styleCriteria);
        }

        if ($eventCriteria !== null) {
            $qb->leftJoin('l.event', 'e')
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
            $qb->andWhere('l.dateStart >= :startDate AND l.dateEnd <= :endDate')
                ->setParameter('startDate', $startDate)
                ->setParameter('endDate', $endDate);
        }

        if ($department !== null) {
            $qb->andWhere('SUBSTRING(l.cp, 1, 2) = :department')
                ->setParameter('department', $department);
        }

        if ($nbPersonne !== null) {
            $qb->andWhere('l.nbPersonne <= :nbPersonne + 2 AND l.nbPersonne >= :nbPersonne - 2')
                ->setParameter('nbPersonne', $nbPersonne);
        }

        $paginator = new Paginator($qb->getQuery(), $fetchJoinCollection = true);
        $paginator->getQuery()
            ->setFirstResult($limit * ($page - 1)) // set the offset
            ->setMaxResults($limit); // set the limit

        $totalItems = count($paginator);
        $totalPages = ceil($totalItems / $limit);

        return [
            'logements' => $paginator->getIterator()->getArrayCopy(),
            'totalItems' => $totalItems,
            'totalPages' => $totalPages,
        ];
    }

    public function countByEventName(string $name): int
    {
        $qb = $this->createQueryBuilder('l')
            ->select('count(l.id)')
            ->leftJoin('l.event', 'e')
            ->where('e.name = :name')
            ->setParameter('name', $name);

        return (int) $qb->getQuery()->getSingleScalarResult();
    }

    public function countByStyleName(string $name): int
    {
        $qb = $this->createQueryBuilder('l')
            ->select('count(l.id)')
            ->leftJoin('l.style', 's')
            ->where('s.name = :name')
            ->setParameter('name', $name);

        return (int) $qb->getQuery()->getSingleScalarResult();
    }


}
