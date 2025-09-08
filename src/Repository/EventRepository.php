<?php

namespace App\Repository;

use App\Entity\Event;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;


class EventRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Event::class);
    }

    public function findByFilters(?string $country): array
    {
        $qb = $this->createQueryBuilder('e');

        if ($country) {
            $qb->andWhere('e.country = :country')
                ->setParameter('country', $country);
        }

        return $qb->getQuery()->getResult();
    }
}
