<?php

namespace App\Repository;

use App\Entity\TagsToLogement;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<TagsToLogement>
 *
 * @method TagsToLogement|null find($id, $lockMode = null, $lockVersion = null)
 * @method TagsToLogement|null findOneBy(array $criteria, array $orderBy = null)
 * @method TagsToLogement[]    findAll()
 * @method TagsToLogement[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TagsToLogementRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TagsToLogement::class);
    }

    public function save(TagsToLogement $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(TagsToLogement $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

//    /**
//     * @return TagsToLogement[] Returns an array of TagsToLogement objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('t')
//            ->andWhere('t.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('t.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?TagsToLogement
//    {
//        return $this->createQueryBuilder('t')
//            ->andWhere('t.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
