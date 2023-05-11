<?php

namespace App\Repository;

use App\Entity\MoyenTransport;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<MoyenTransport>
 *
 * @method MoyenTransport|null find($id, $lockMode = null, $lockVersion = null)
 * @method MoyenTransport|null findOneBy(array $criteria, array $orderBy = null)
 * @method MoyenTransport[]    findAll()
 * @method MoyenTransport[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MoyenTransportRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, MoyenTransport::class);
    }

    public function save(MoyenTransport $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(MoyenTransport $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

//    /**
//     * @return MoyenTransport[] Returns an array of MoyenTransport objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('m')
//            ->andWhere('m.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('m.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?MoyenTransport
//    {
//        return $this->createQueryBuilder('m')
//            ->andWhere('m.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }

public function findmoybyid($id)
    {

            return $this->createQueryBuilder('m')
            ->where('m.id LIKE :id')
            ->setParameter('id', '%'.$id.'%')
            ->getQuery()
            ->getResult();
    }
    public function findByTypeVehicule($type)
{
    return $this->createQueryBuilder('v')
        ->where('v.type_vehicule = :type')
        ->setParameter('type', $type)
        ->getQuery()
        ->getResult();
}
public function countByTypeVehicule()
{
    return $this->createQueryBuilder('mt')
        ->select('mt.type_vehicule, COUNT(mt.id) AS count')
        ->groupBy('mt.type_vehicule')
        ->getQuery()
        ->getResult();
}

public function findByMarque($marque)
{
    return $this->createQueryBuilder('m')
        ->where('m.marque LIKE :marque')
        ->setParameter('marque', '%'.$marque.'%')
        ->getQuery()
        ->getResult();
}

public function vehiculeTypeStats()
{
    $em = $this->getDoctrine()->getManager();
    $data = $em->getRepository('AppBundle:Vehicule')->createQueryBuilder('v')
        ->select('v.TypeVehicule, COUNT(v.id) as count')
        ->groupBy('v.TypeVehicule')
        ->getQuery()
        ->getResult();
    
    return $this->render('stats/vehiculeTypeStats.html.twig', [
        'data' => $data
    ]);
}

        
}
