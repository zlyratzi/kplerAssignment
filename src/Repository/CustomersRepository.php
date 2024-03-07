<?php

namespace App\Repository;

use App\Entity\Customers;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Customers>
 *
 * @method Customers|null find($id, $lockMode = null, $lockVersion = null)
 * @method Customers|null findOneBy(array $criteria, array $orderBy = null)
 * @method Customers[]    findAll()
 * @method Customers[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CustomersRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Customers::class);
    }

    public function add(Customers $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Customers $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function findCustomersByCustomCriteria(array $criteria = [], $queryBuilder = null)
    {
        $buildResults = false;
        if (is_null($queryBuilder) || empty($queryBuilder)) {
            $queryBuilder = $this->createQueryBuilder('c');
            $buildResults = true;
        }

        if (isset($criteria['country'])) {
            $queryBuilder->andWhere('c.country LIKE :country')
                ->setParameter('country', '%' . $criteria['country'] . '%');
        }

        if (isset($criteria['currency'])) {
            $queryBuilder->andWhere('c.currency = :currency')
                ->setParameter('currency', $criteria['currency']);
        }

        if (isset($criteria['orderBy'])) {
            $orderBy = $criteria['orderBy'];
            $direction = strtoupper($criteria['direction']) === 'DESC' ? 'DESC' : 'ASC';
            $queryBuilder->orderBy('c.' . $orderBy, $direction);
        }

        if ($buildResults) {
            return $queryBuilder->getQuery()->getResult();
        }

        return $queryBuilder;
    }


//    /**
//     * @return Customers[] Returns an array of Customers objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('s')
//            ->andWhere('s.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('s.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Customers
//    {
//        return $this->createQueryBuilder('s')
//            ->andWhere('s.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
