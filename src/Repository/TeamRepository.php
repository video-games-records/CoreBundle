<?php

declare(strict_types=1);

namespace VideoGamesRecords\CoreBundle\Repository;

use Doctrine\ORM\NonUniqueResultException;
use Doctrine\Persistence\ManagerRegistry;
use VideoGamesRecords\CoreBundle\Entity\Team;

class TeamRepository extends DefaultRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Team::class);
    }

    /**
     * @return int|mixed|string|null
     * @throws NonUniqueResultException
     */
    public function getStats(): mixed
    {
        $qb = $this->createQueryBuilder('team')
            ->select('COUNT(team.id)');
        $qb->where('team.pointChart > 0');

        return $qb->getQuery()
            ->getOneOrNullResult();
    }
}
