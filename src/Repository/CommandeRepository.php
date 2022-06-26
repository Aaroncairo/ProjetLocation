<?php

namespace App\Repository;

use App\Entity\Commande;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Commande>
 *
 * @method Commande|null find($id, $lockMode = null, $lockVersion = null)
 * @method Commande|null findOneBy(array $criteria, array $orderBy = null)
 * @method Commande[]    findAll()
 * @method Commande[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CommandeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Commande::class);
    }

    public function add(Commande $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Commande $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function listeVehiculeLoue($dtDebutResa, $dtFinResa):array{

                    $resultat = [];
                    $vehiculeReservee =  $this->createQueryBuilder('c')
                    //->select("c.id AS idResa", "c.dateHeureDepart", "c.dateHeureFin","v.id AS idVehicule", "v.titre")
                    ->select("v.id AS idVehicule")
                    ->leftJoin("c.vehicule", "v")
                    ->orWhere('c.dateHeureDepart BETWEEN :dtDebut AND :dtFin')
                    ->orWhere('c.dateHeureFin BETWEEN :dtDebut AND :dtFin')
                    ->orWhere('c.dateHeureDepart <= :dtDebut AND c.dateHeureFin >= :dtFin')
                    ->orWhere('c.dateHeureDepart >= :dtDebut AND c.dateHeureFin <= :dtFin')
                    ->setParameter('dtDebut', $dtDebutResa)
                    ->setParameter('dtFin', $dtFinResa)
                    ->getQuery()
                    ->getResult()
                ;
        foreach($vehiculeReservee as $vehicule){
            $resultat[] = $vehicule["idVehicule"];
        }
        return $resultat;

    }
    
    public function vehiculeDisponibles($dtDebutResa , $dtFinResa){
        $reserves = $this->listeVehiculeLoue($dtDebutResa , $dtFinResa);
        // dump($reserves);

        $qb = $this->getEntityManager()->createQueryBuilder();
        //return [];
        return $this->createQueryBuilder('c')
                    ->select("v.id")
                    ->leftjoin( "c.vehicule" , "v" )
                    ->where($qb->expr()->notIn('v.id', $reserves))
                    ->getQuery()
                    ->getResult();
  
    }

//    /**
//     * @return Commande[] Returns an array of Commande objects
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

//    public function findOneBySomeField($value): ?Commande
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
