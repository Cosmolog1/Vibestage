<?php

namespace App\Repository;

use App\Entity\Artiste;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class ArtisteRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Artiste::class);
    }

    public function findByFilters(?string $country, ?int $categoryId): array
    {
        $qb = $this->createQueryBuilder('a');

        if ($country) {
            $qb->andWhere('a.country = :country')
                ->setParameter('country', $country);
        }

        if ($categoryId) {
            $qb->join('a.categories', 'c')
                ->andWhere('c.id = :categoryId')
                ->setParameter('categoryId', $categoryId);
        }

        return $qb->getQuery()->getResult();
    }

    public function findDistinctCountries(): array
    {
        return array_column(
            $this->createQueryBuilder('a')
                ->select('DISTINCT a.country')
                ->getQuery()
                ->getScalarResult(),
            'country'
        );
    }


    public function findDistinctCategories(): array
    {
        return array_column(
            $this->createQueryBuilder('a')
                ->select('DISTINCT a.category')
                ->getQuery()
                ->getScalarResult(),
            'category'
        );
    }
}
