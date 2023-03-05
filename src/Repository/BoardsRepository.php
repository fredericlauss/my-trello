<?php

namespace App\Repository;

use App\Entity\Boards;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Boards>
 *
 * @method Boards|null find($id, $lockMode = null, $lockVersion = null)
 * @method Boards|null findOneBy(array $criteria, array $orderBy = null)
 * @method Boards[]    findAll()
 * @method Boards[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BoardsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Boards::class);
    }

    public function save(Boards $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Boards $board, bool $flush = false)
    {
        // Delete des tickets et des columns
        $columns = $board->getColumns();
        foreach ($columns as $column) {
            $tickets = $column->getTickets();
            foreach ($tickets as $ticket) {
                $this->_em->remove($ticket);
            }
            $this->_em->remove($column);
        }
    
        // Delete du board
        $this->_em->remove($board);
    
        if ($flush) {
            $this->_em->flush();
        }
    }
//    /**
//     * @return Boards[] Returns an array of Boards objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('b')
//            ->andWhere('b.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('b.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Boards
//    {
//        return $this->createQueryBuilder('b')
//            ->andWhere('b.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
