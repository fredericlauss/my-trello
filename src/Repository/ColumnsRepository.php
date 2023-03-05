<?php

namespace App\Repository;

use App\Entity\Columns;
use App\Entity\Tickets;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Columns>
 *
 * @method Columns|null find($id, $lockMode = null, $lockVersion = null)
 * @method Columns|null findOneBy(array $criteria, array $orderBy = null)
 * @method Columns[]    findAll()
 * @method Columns[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ColumnsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Columns::class);
    }

    public function save(Columns $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Columns $column, bool $flush = false)
    {
        // delete du ticket
        $tickets = $column->getTickets();
        foreach ($tickets as $ticket) {
            $this->_em->remove($ticket);
        }

        // delte de la columns
        $this->_em->remove($column);

        if ($flush) {
            $this->_em->flush();
        }
    }
    public function removeTicket(Tickets $ticket): void
    {
        $this->getEntityManager()->remove($ticket);
        $this->getEntityManager()->flush();
    }
    


//    /**
//     * @return Columns[] Returns an array of Columns objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('c.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Columns
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
