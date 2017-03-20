<?php

namespace VideoGamesRecords\CoreBundle\Repository;

use Doctrine\ORM\EntityRepository;
use VideoGamesRecords\CoreBundle\Entity\Player;
use VideoGamesRecords\CoreBundle\Tools\Ranking;

class PlayerRepository extends EntityRepository
{
    /**
     * @param \AppBundle\Entity\User $user
     * @return \VideoGamesRecords\CoreBundle\Entity\Player
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function getPlayerFromUser($user)
    {
        $qb = $this->createQueryBuilder('player')
            ->where('player.normandieUser = :userId')
            ->setParameter('userId', $user->getId());

        $player = $qb->getQuery()->getOneOrNullResult();

        return (null !== $player) ? $player : $this->createPlayerFromUser($user);
    }

    /**
     * @param array $params
     * @return mixed
     */
    public function getNbPlayer($params = [])
    {
        $qb = $this->createQueryBuilder('player')
            ->select('COUNT(player.idPlayer)');

        if (array_key_exists('nbChart>0', $params)) {
            $qb->where('player.nbChart > 0');
        }

        return $qb->getQuery()
            ->getSingleScalarResult();
    }

    /**
     * @param int $idPlayer
     */
    public function maj($idPlayer)
    {
        $query = $this->_em->createQuery("
            SELECT
                 pg.idPlayer,
                 SUM(pg.chartRank0) as chartRank0,
                 SUM(pg.chartRank1) as chartRank1,
                 SUM(pg.chartRank2) as chartRank2,
                 SUM(pg.chartRank3) as chartRank3,
                 SUM(pg.nbChart) as nbChart,
                 SUM(pg.nbChartProven) as nbChartProven,
                 SUM(pg.pointChart) as pointChart,
                 SUM(pg.pointGame) as pointGame
            FROM VideoGamesRecords\CoreBundle\Entity\PlayerGame pg
            WHERE pg.idPlayer = :idPlayer
            GROUP BY pg.idPlayer");

        $query->setParameter('idPlayer', $idPlayer);
        $result = $query->getResult();
        $row = $result[0];

        $player = $this->_em->find('VideoGamesRecords\CoreBundle\Entity\Player', $idPlayer);

        $player->setChartRank0($row['chartRank0']);
        $player->setChartRank1($row['chartRank1']);
        $player->setChartRank2($row['chartRank2']);
        $player->setChartRank3($row['chartRank3']);
        $player->setNbChart($row['nbChart']);
        $player->setNbChartProven($row['nbChartProven']);
        $player->setPointChart($row['pointChart']);
        $player->setPointGame($row['pointGame']);

        $this->_em->persist($player);
        $this->_em->flush($player);
    }

    public function majGameRank()
    {
        $data = [];

        //----- data rank0
        $query = $this->_em->createQuery("
            SELECT
                 pg.idPlayer,
                 COUNT(pg.idGame) as nb
            FROM VideoGamesRecords\CoreBundle\Entity\PlayerGame pg
            JOIN pg.game g
            WHERE pg.rankPointChart = 1
            AND g.nbPlayer > 1
            AND pg.nbEqual = 1
            GROUP BY pg.idPlayer");

        $result = $query->getResult();
        foreach ($result as $row) {
            $data['gameRank0'][$row['idPlayer']] = (int) $row['nb'];
        }

        //----- data rank1 to rank3
        $query = $this->_em->createQuery("
            SELECT
                 pg.idPlayer,
                 COUNT(pg.idGame) as nb
            FROM VideoGamesRecords\CoreBundle\Entity\PlayerGame pg
            WHERE pg.rankPointChart = :rank
            GROUP BY pg.idPlayer");

        for ($i = 1; $i <= 3; $i++) {
            $query->setParameter('rank', $i);
            $result = $query->getResult();
            foreach ($result as $row) {
                $data["gameRank$i"][$row['idPlayer']] = (int) $row['nb'];
            }
        }

        /** @var \VideoGamesRecords\CoreBundle\Entity\Player[] $players */
        $players = $this->findAll();

        foreach ($players as $player) {
            $idPlayer = $player->getIdPlayer();

            $rank0 = (isset($data['gameRank0'][$idPlayer])) ? $data['gameRank0'][$idPlayer] : 0;
            $rank1 = (isset($data['gameRank1'][$idPlayer])) ? $data['gameRank1'][$idPlayer] : 0;
            $rank2 = (isset($data['gameRank2'][$idPlayer])) ? $data['gameRank2'][$idPlayer] : 0;
            $rank3 = (isset($data['gameRank3'][$idPlayer])) ? $data['gameRank3'][$idPlayer] : 0;

            $player->setGameRank0($rank0);
            $player->setGameRank1($rank1);
            $player->setGameRank2($rank2);
            $player->setGameRank3($rank3);
        }
        $this->getEntityManager()->flush();
    }

    /**
     * Update column rankPointChart
     */
    public function majRankPointChart()
    {
        $players = $this->findBy(array(), array('pointChart' => 'DESC'));

        $list = array();
        foreach ($players as $player) {
            $list[] = $player;
        }

        Ranking::addObjectRank($list, 'rankPointChart', array('pointChart'));
        $this->getEntityManager()->flush();
    }

    /**
     * Update column rankPointGame
     */
    public function majRankPointGame()
    {
        $players = $this->findBy(array(), array('pointGame' => 'DESC'));

        $list = array();
        foreach ($players as $player) {
            $list[] = $player;
        }

        Ranking::addObjectRank($list, 'rankPointGame', array('pointGame'));
        $this->getEntityManager()->flush();
    }

    /**
     * Update column rankMedal
     */
    public function majRankMedal()
    {
        $players = $this->findBy(array(), array('chartRank0' => 'DESC', 'chartRank1' => 'DESC', 'chartRank2' => 'DESC', 'chartRank3' => 'DESC'));

        $list = array();
        foreach ($players as $player) {
            $list[] = $player;
        }

        Ranking::addObjectRank($list, 'rankMedal', array('chartRank0', 'chartRank1', 'chartRank2', 'chartRank3'));
        $this->getEntityManager()->flush();
    }

    /**
     * Update column rankCup
     */
    public function majRankCup()
    {
        $this->majGameRank();
        $players = $this->findBy(array(), array('gameRank0' => 'DESC', 'gameRank1' => 'DESC', 'gameRank2' => 'DESC', 'gameRank3' => 'DESC'));

        $list = array();
        foreach ($players as $player) {
            $list[] = $player;
        }

        Ranking::addObjectRank($list, 'rankCup', array('gameRank0', 'gameRank1', 'gameRank2', 'gameRank3'));
        $this->getEntityManager()->flush();
    }


    /**
     * Update column rankProof
     */
    public function majRankProof()
    {
        $players = $this->findBy(array(), array('nbChartProven' => 'DESC'));

        $list = array();
        foreach ($players as $player) {
            $list[] = $player;
        }

        Ranking::addObjectRank($list, 'rankProof', array('nbChartProven'));
        $this->getEntityManager()->flush();
    }

    /**
     * @param \AppBundle\Entity\User $user
     * @return \VideoGamesRecords\CoreBundle\Entity\Player
     */
    private function createPlayerFromUser($user)
    {
        $player = new Player();
        $player
            ->setNormandieUser($user)
            ->setPseudo($user->getUsername());

        $this->getEntityManager()->persist($player);
        $this->getEntityManager()->flush();

        return $player;
    }

    /**
     * @param int $idPlayer
     * @return array
     */
    public function getRankingPointsChart($idPlayer)
    {
        $query = $this->createQueryBuilder('p')
            ->orderBy('p.rankPointChart');

        if ($idPlayer !== null) {
            $query->where('(p.rankPointChart <= :maxRank OR p.idPlayer = :idPlayer)')
                ->setParameter('maxRank', 100)
                ->setParameter('idPlayer', $idPlayer);
        } else {
            $query->where('p.rankPointChart <= :maxRank')
                ->setParameter('maxRank', 100);
        }

        return $query->getQuery()->getResult();
    }


    /**
     * @param int $idPlayer
     * @return array
     */
    public function getRankingPointsGame($idPlayer)
    {
        $query = $this->createQueryBuilder('p')
            ->orderBy('p.rankPointGame');

        if ($idPlayer !== null) {
            $query->where('(p.rankPointGame <= :maxRank OR p.idPlayer = :idPlayer)')
                ->setParameter('maxRank', 100)
                ->setParameter('idPlayer', $idPlayer);
        } else {
            $query->where('p.rankPointGame <= :maxRank')
                ->setParameter('maxRank', 100);
        }

        return $query->getQuery()->getResult();
    }


    /**
     * @param int $idPlayer
     * @return array
     */
    public function getRankingMedals($idPlayer)
    {
        $query = $this->createQueryBuilder('p')
            ->orderBy('p.rankMedal');

        if ($idPlayer !== null) {
            $query->where('(p.rankMedal <= :maxRank OR p.idPlayer = :idPlayer)')
                ->setParameter('maxRank', 100)
                ->setParameter('idPlayer', $idPlayer);
        } else {
            $query->where('p.rankMedal <= :maxRank')
                ->setParameter('maxRank', 100);
        }

        return $query->getQuery()->getResult();
    }


    /**
     * @param int $idPlayer
     * @return array
     */
    public function getRankingCups($idPlayer)
    {
        $query = $this->createQueryBuilder('p')
            ->orderBy('p.rankCup');

        if ($idPlayer !== null) {
            $query->where('(p.rankCup <= :maxRank OR p.idPlayer = :idPlayer)')
                ->setParameter('maxRank', 100)
                ->setParameter('idPlayer', $idPlayer);
        } else {
            $query->where('p.rankCup <= :maxRank')
                ->setParameter('maxRank', 100);
        }

        return $query->getQuery()->getResult();
    }

    /**
     * @throws \Doctrine\DBAL\DBALException
     */
    public function majNbGame()
    {
        //----- MAJ game.nbTeam
        $sql = "UPDATE vgr_player p SET nbGame = (SELECT COUNT(idGame) FROM vgr_player_game pg WHERE pg.idPlayer = p.idPlayer)";
        $this->_em->getConnection()->executeUpdate($sql);
    }
}
