<?php

namespace App\Repository;

use App\Entity\Invoices;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Invoices>
 *
 * @method Invoices|null find($id, $lockMode = null, $lockVersion = null)
 * @method Invoices|null findOneBy(array $criteria, array $orderBy = null)
 * @method Invoices[]    findAll()
 * @method Invoices[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class InvoicesRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Invoices::class);
    }

    public function add(Invoices $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Invoices $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function findInvoicesByCustomer($customerId, array $criteria = [])
    {
        $queryBuilder = $this->createQueryBuilder('inv')
            ->where('inv.customer = :customerId')
            ->setParameter('customerId', $customerId);
        
        if (!empty($criteria)) {
            $queryBuilder = $this->findInvoicesByCustomCriteria($criteria, $queryBuilder);
        }

        return $queryBuilder->getQuery()->getResult();
    }

    public function findInvoicesByCustomCriteria(array $criteria = [], $queryBuilder = null)
    {
        $buildResults = false;
        if (is_null($queryBuilder) || empty($queryBuilder)) {
            $queryBuilder = $this->createQueryBuilder('inv');
            $buildResults = true;
        }

        if (isset($criteria['start_date'])) {
            $queryBuilder->andWhere('inv.invoiceDate >= :startDate')
                ->setParameter('startDate', new \DateTime($criteria['start_date']));
        }

        if (isset($criteria['end_date'])) {
            $queryBuilder->andWhere('inv.invoiceDate <= :endDate')
                ->setParameter('endDate', new \DateTime($criteria['end_date']));
        }

        if (isset($criteria['date'])) {
            $queryBuilder->andWhere('inv.invoiceDate = :date')
                ->setParameter('date', new \DateTime($criteria['date']));
        }

        if (isset($criteria['min_amount'])) {
            $queryBuilder->andWhere('inv.amount >= :minAmount')
                ->setParameter('minAmount', $criteria['min_amount']);
        }
    
        if (isset($criteria['max_amount'])) {
            $queryBuilder->andWhere('inv.amount <= :maxAmount')
                ->setParameter('maxAmount', $criteria['max_amount']);
        }

        if (isset($criteria['amount'])) {
            $queryBuilder->andWhere('inv.amount = :amount')
                ->setParameter('amount', $criteria['amount']);
        }
        
        if (isset($criteria['is_paid'])) {
            $queryBuilder->andWhere('inv.isPaid = :isPaid')
                ->setParameter('isPaid', $criteria['is_paid']);
        }

        if (isset($criteria['orderBy'])) {
            $orderBy = $criteria['orderBy'];
            $direction = 'ASC';
            if (isset($criteria['direction'])) {
                $direction = strtoupper($criteria['direction']) === 'DESC' ? 'DESC' : 'ASC';
            }
            
            $queryBuilder->orderBy('inv.' . $orderBy, $direction);
        }

        if ($buildResults) {
            return $queryBuilder->getQuery()->getResult();
        }
        return $queryBuilder;
    }

    public function markAsPaid($id)
    {
        $qb = $this->createQueryBuilder('inv')
            ->update('App\Entity\Invoices', 'inv')
            ->set('inv.isPaid', true)
            ->where('inv.id = :id')
            ->setParameter('id', $id);

        return $qb->getQuery()->execute();
    }

    public function getMonthlyRevenueData(): array
    {
        $results = $this->createQueryBuilder('inv')
            ->select('inv.invoiceDate, inv.amount')
            ->orderBy('inv.invoiceDate', 'ASC')
            ->getQuery()
            ->getResult();

        $revenue = [];
        foreach ($results as $result) {
            $formattedDate = $result['invoiceDate']->format('m-Y');
            list($month, $year) = explode('-', $formattedDate);
            $amount = $result['amount'];

            if (!isset($revenue[$year][$month])) {
                $revenue[$year][$month] = 0;
            }

            $revenue[$year][$month] += $amount;
        }
        return $revenue;
    }
    

//    /**
//     * @return Invoices[] Returns an array of Invoices objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('k')
//            ->andWhere('k.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('k.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Invoices
//    {
//        return $this->createQueryBuilder('k')
//            ->andWhere('k.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
