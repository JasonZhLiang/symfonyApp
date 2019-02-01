<?php

namespace App\Repository;

use App\Entity\Article;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Article|null find($id, $lockMode = null, $lockVersion = null)
 * @method Article|null findOneBy(array $criteria, array $orderBy = null)
 * @method Article[]    findAll()
 * @method Article[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ArticleRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Article::class);
    }

//    /**
//     * @return Article[] Returns an array of Article objects
//     */
//    public function findAllPublishedOrderedByNewest()
//    {
//        return $this->createQueryBuilder('a')
//            ->andWhere('a.publishedAt IS NOT NULL OR a.publishedAt > CURRENT_TIMESTAMP()')
//            ->orderBy('a.publishedAt', 'DESC')
//            ->getQuery()
//            ->getResult()
//        ;
//    }

    /*
    public function findOneBySomeField($value): ?Article
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */

    /**
     * @return Article[] Returns an array of Article objects
     */
    public function findAllPublishedOrderedByNewest()
    {
//        $qb = $this->createQueryBuilder('a');
//        return $this->addIsPublishedQueryBuilder($qb)
            return $this->addIsPublishedQueryBuilder()
//            ->andWhere('a.publishedAt IS NOT NULL OR a.publishedAt > CURRENT_TIMESTAMP()')
            ->orderBy('a.publishedAt', 'DESC')
            ->getQuery()
            ->getResult()
            ;
    }

    private function addIsPublishedQueryBuilder(QueryBuilder $qb = null)
    {
//        return $qb->andWhere('a.publishedAt IS NOT NULL OR a.publishedAt > CURRENT_TIMESTAMP()');
        return $this->getOrCreateQueryBuilder($qb)
            ->andWhere('a.publishedAt IS NOT NULL OR a.publishedAt > CURRENT_TIMESTAMP()');
    }

    private function getOrCreateQueryBuilder(QueryBuilder $qb = null)
    {
        return $qb ?: $this->createQueryBuilder('a');
    }
}
