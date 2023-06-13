<?php

namespace VideoGamesRecords\CoreBundle\Controller\Ranking;

use Doctrine\ORM\Exception\ORMException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use VideoGamesRecords\CoreBundle\Contracts\Ranking\RankingProviderInterface;
use VideoGamesRecords\CoreBundle\Entity\Group;

/**
 * Class TeamGroupController
 */
class TeamGroupController extends AbstractController
{
    private RankingProviderInterface $rankingProvider;

    public function __construct(RankingProviderInterface $rankingProvider)
    {
        $this->rankingProvider = $rankingProvider;
    }

    /**
     * @param Group $group
     * @param Request $request
     * @return array
     */
    public function getRankingPoints(Group $group, Request $request): array
    {
        return $this->rankingProvider->getRankingPoints(
            $group->getId(),
            [
                'maxRank' => $request->query->get('maxRank', 5),
            ]
        );
    }

    /**
     * @param Group    $group
     * @param Request $request
     * @return array
     */
    public function getRankingMedals(Group $group, Request $request): array
    {
        return $this->rankingProvider->getRankingMedals(
            $group->getId(),
            [
                'maxRank' => $request->query->get('maxRank', 5),
            ]
        );
    }
}
