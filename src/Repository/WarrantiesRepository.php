<?php

namespace App\Repository;

use App\Entity\Warranties;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method Warranties|null find($id, $lockMode = null, $lockVersion = null)
 * @method Warranties|null findOneBy(array $criteria, array $orderBy = null)
 * @method Warranties[]    findAll()
 * @method Warranties[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class WarrantiesRepository extends ServiceEntityRepository
{
    /**
     * WarrantiesRepository constructor.
     * @param ManagerRegistry $registry
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Warranties::class);
    }

}
