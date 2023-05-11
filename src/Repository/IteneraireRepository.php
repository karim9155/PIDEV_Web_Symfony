<?php

namespace App\Repository;

use App\Entity\Iteneraire;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Iteneraire>
 *
 * @method Iteneraire|null find($id, $lockMode = null, $lockVersion = null)
 * @method Iteneraire|null findOneBy(array $criteria, array $orderBy = null)
 * @method Iteneraire[]    findAll()
 * @method Iteneraire[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class IteneraireRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Iteneraire::class);
    }

    public function save(Iteneraire $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Iteneraire $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

//    /**
//     * @return Iteneraire[] Returns an array of Iteneraire objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('i')
//            ->andWhere('i.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('i.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Iteneraire
//    {
//        return $this->createQueryBuilder('i')
//            ->andWhere('i.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }

/**
 * Returns an array of trajets that match the given search term.
 *
 * @param string|null $searchTerm
 * @return Trajet[]
 */
public function findBySearchTerm(?string $searchTerm): array
{
    $queryBuilder = $this->createQueryBuilder('t');

    if ($searchTerm) {
        $queryBuilder->andWhere('t.id LIKE :searchTerm')
                     ->setParameter('searchTerm', '%'.$searchTerm.'%');
    }

    return $queryBuilder->getQuery()->getResult();
}
}
