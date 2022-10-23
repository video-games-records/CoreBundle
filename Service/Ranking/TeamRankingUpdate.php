<?php

namespace VideoGamesRecords\CoreBundle\Service\Ranking;

use Doctrine\ORM\EntityManagerInterface;
use VideoGamesRecords\CoreBundle\Interface\RankingUpdateInterface;
use VideoGamesRecords\CoreBundle\Tools\Ranking;

class TeamRankingUpdate implements RankingUpdateInterface
{
    private EntityManagerInterface $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function maj(int $id): void
    {
        $team = $this->em->getRepository('VideoGamesRecords\CoreBundle\Entity\Team')->find($id);
        if (null === $team) {
            return;
        }
        
        $query = $this->em->createQuery("
            SELECT
                 t.id,
                 SUM(tg.chartRank0) as chartRank0,
                 SUM(tg.chartRank1) as chartRank1,
                 SUM(tg.chartRank2) as chartRank2,
                 SUM(tg.chartRank3) as chartRank3,
                 SUM(tg.pointChart) as pointChart,
                 SUM(tg.pointGame) as pointGame,
                 COUNT(DISTINCT tg.game) as nbGame
            FROM VideoGamesRecords\CoreBundle\Entity\TeamGame tg
            JOIN tg.team t
            WHERE tg.team = :team
            GROUP BY t.id");

        $query->setParameter('team', $team);
        $result = $query->getResult();
        if ($result) {
            $row = $result[0];

            $team->setChartRank0($row['chartRank0']);
            $team->setChartRank1($row['chartRank1']);
            $team->setChartRank2($row['chartRank2']);
            $team->setChartRank3($row['chartRank3']);
            $team->setPointChart($row['pointChart']);
            $team->setPointGame($row['pointGame']);
            $team->setNbGame($row['nbGame']);

            $this->em->persist($team);
            $this->em->flush();
        }
    }

    /**
     * @return void
     */
    public function majRank(): void
    {
        $this->majRankPointChart();
        $this->majRankMedal();
    }
     /**
     * Update column rankPointChart
     */
    private function majRankPointChart()
    {
        $players = $this->em->getRepository('VideoGamesRecords\CoreBundle\Entity\Player')->findBy(array(), array('pointChart' => 'DESC'));
        Ranking::addObjectRank($players, 'rankPointChart', array('pointChart'));
        $this->em->flush();
    }

    /**
     * Update column rankMedal
     */
    private function majRankMedal()
    {
        $players = $this->em->getRepository('VideoGamesRecords\CoreBundle\Entity\Player')->findBy(array(), array('chartRank0' => 'DESC', 'chartRank1' => 'DESC', 'chartRank2' => 'DESC', 'chartRank3' => 'DESC'));
        Ranking::addObjectRank($players, 'rankMedal', array('chartRank0', 'chartRank1', 'chartRank2', 'chartRank3'));
        $this->em->flush();
    }
}
