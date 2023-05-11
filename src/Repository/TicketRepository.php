<?php

namespace App\Repository;

use App\Entity\Ticket;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Ticket>
 *
 * @method Ticket|null find($id, $lockMode = null, $lockVersion = null)
 * @method Ticket|null findOneBy(array $criteria, array $orderBy = null)
 * @method Ticket[]    findAll()
 * @method Ticket[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TicketRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Ticket::class);
    }

    public function save(Ticket $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Ticket $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    //    /**
    //     * @return Ticket[] Returns an array of Ticket objects
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

    //    public function findOneBySomeField($value): ?Ticket
    //    {
    //        return $this->createQueryBuilder('t')
    //            ->andWhere('t.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }


    /*public function searchTickets($searchTerm)
{
    $qb = $this->createQueryBuilder('t');
    $qb->andWhere('t.nom_ticket LIKE :searchTerm OR t.prix LIKE :searchTerm')
       ->setParameter('searchTerm', '%'.$searchTerm.'%')
       ->orderBy('t.id', 'ASC');
    return $qb->getQuery()->getResult();
}*/
    public function searchTickets($searchValue)
    {
        $qb = $this->createQueryBuilder('t')
            ->where('t.nomTicket LIKE :searchValue OR t.prix LIKE :searchValue OR t.action LIKE :searchValue')
            ->setParameter('searchValue', '%' . $searchValue . '%')
            ->orderBy('t.id', 'ASC')
            ->getQuery();

        return $qb->execute();
    }
    public function searchTicketsby($searchValue)
    {
        return $this->createQueryBuilder('t')
            ->where('t.nom_ticket LIKE :searchValue')
            ->orWhere('t.prix LIKE :searchValue')
            ->setParameter('searchValue', '%' . $searchValue . '%')
            ->orderBy('t.id', 'ASC')
            ->getQuery();
    }
}
