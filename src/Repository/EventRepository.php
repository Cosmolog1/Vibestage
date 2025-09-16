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

    public function findByFilters(?string $eventCountry): array
    {
        $qb = $this->createQueryBuilder('e')
            ->leftJoin('e.location', 'l')
            ->addSelect('l');

        if ($eventCountry) {
            $qb->andWhere('l.country = :country')
                ->setParameter('country', $eventCountry);
        }

        return $qb->getQuery()->getResult();
    }
}
