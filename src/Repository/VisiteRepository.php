<?php

namespace App\Repository;

use App\Entity\Visite;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
/*use Doctrine\ORM\Mapping;
/*use Doctrine\ORM\EntityRepository;

/**
 * @extends ServiceEntityRepository<Visite>
 * @method Visite|null find($id, $lockMode = null, $lockVersion = null)
 * @method Visite|null findOneBy(array $criteria, array $orderBy = null)
 * @method Visite[]    findAll()
 * @method Visite[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class VisiteRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Visite::class);
    }

    //    /**
    //     * @return Visite[] Returns an array of Visite objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('v')
    //            ->andWhere('v.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('v.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Visite
    //    {
    //        return $this->createQueryBuilder('v')
    //            ->andWhere('v.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
    
    /**
     * Retourne toutes les visites triées sur un champ
     * @param type $champ
     * @param type $ordre
     * @return Visite[]
     */

    public function findAllOrderBy(string $champ, string $ordre, ?string $valeur = null): array {
	$qb = $this->createQueryBuilder('v');
        
        $allowFields = ['ville', 'pays', 'note', 'datecreation'];
        if (!in_array($champ, $allowFields)) {
            throw new \InvalidArgumentException("Invali sorting field: $champ");
        }

	if ($valeur) {
		$qb->where('v.' . $champ . 'LIKE : valeur')
		   ->setParameter('valeur', '%' . $valeur . '%');
	}
        
        if ($champ === 'note' || $champ === 'datecreation') {
            $ordre = $ordre === 'ASC' ? 'DESC' : 'ASC';
        }

	$qb->orderBy('v.' . $champ, $ordre);

	return $qb->getQuery()->getResult();

        
    }
    
    /**
     * Enregistrements dont un champ est égal à une valeur
     * ou tous les enregistrements si la valeur est vide
     * @param type $champ
     * @param type $valeur
     * @return Visite[]
     */
    
    public function findByEqualValue($champ, $valeur) : array {
        if ($valeur=="") {
            return $this->createQueryBuilder('v')
                    ->orderBy('v.' .$champ, 'ASC')
                    ->getQuery()
                    ->getResult();
        } else {
            return $this->createQueryBuilder('v')
                    ->where('v.' .$champ.'=:valeur')
                    ->setParameter('valeur', $valeur)
                    ->orderBy('v.datecreation', 'DESC')
                    ->getQuery()
                    ->getResult();
        }
    }
    
    /**
     * Supprime une visite
     * @param Visite $visite
     * @return void
     */
    
    public function remove(Visite $visite) : void {
        $this->getEntityManager()->remove($visite);
        $this->getEntityManager()->flush();
    }
}


