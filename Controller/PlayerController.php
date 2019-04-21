<?php

namespace VideoGamesRecords\CoreBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Cache;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

/**
 * Class PlayerController
 * @Route("/player")
 */
class PlayerController extends Controller
{
    /**
     * @Route("/{id}/{slug}", requirements={"id": "[1-9]\d*"}, name="vgr_player_index")
     * @Method("GET")
     * @Cache(smaxage="10")
     *
     * @param int $id
     * @param string $slug
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexAction($id, $slug)
    {
        $player = $this->getDoctrine()->getRepository('VideoGamesRecordsCoreBundle:Player')->getPlayerWithGames($id);
        if ($slug !== $player->getSlug()) {
            return $this->redirectToRoute('vgr_player_index', ['id' => $player->getIdPlayer(), 'slug' => $player->getSlug()], 301);
        }

        $nbPlayer = $this->getDoctrine()->getRepository('VideoGamesRecordsCoreBundle:Player')->getNbPlayer(['nbChart>0' => true]);

        $rows = $this->getDoctrine()->getRepository('VideoGamesRecordsCoreBundle:PlayerChart')->getRows(
            [
                'idPlayer' => $id,
                'limit' => 1,
                'orderBy' => [
                    'column' => 'pc.dateModif',
                    'order' => 'DESC',
                ],
            ]
        );
        if (count($rows) == 1) {
            $lastChart = $rows[0];
        } else {
            $lastChart = null;
        }

        //----- breadcrumbs
        $breadcrumbs = $this->get('white_october_breadcrumbs');
        $breadcrumbs->addRouteItem('Home', 'homepage');
        $breadcrumbs->addItem($player->getPseudo());

        return $this->render(
            'VideoGamesRecordsCoreBundle:Player:index.html.twig',
            [
                'player' => $player,
                'nbPlayer' => $nbPlayer,
                'lastChart' => $lastChart
            ]
        );
    }

    /**
     * @Route("/ranking-points-chart", name="vgr_player_ranking_points_chart")
     * @Method("GET")
     * @Cache(smaxage="10")
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function rankingPointsChartAction()
    {
        $ranking = $this->getDoctrine()->getRepository('VideoGamesRecordsCoreBundle:Player')->getRankingPointsChart($this->getIdPlayer());
        $breadcrumbs = $this->get('white_october_breadcrumbs');
        $breadcrumbs->addRouteItem('Home', 'homepage');
        $breadcrumbs->addItem('player.pointchartranking.full');

        return $this->render('VideoGamesRecordsCoreBundle:Ranking:player-points-chart.html.twig', ['ranking' => $ranking]);
    }

    /**
     * @Route("/ranking-points-game", name="vgr_player_ranking_points_game")
     * @Method("GET")
     * @Cache(smaxage="10")
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function rankingPointsGameAction()
    {
        $ranking = $this->getDoctrine()->getRepository('VideoGamesRecordsCoreBundle:Player')->getRankingPointsGame($this->getIdPlayer());
        $breadcrumbs = $this->get('white_october_breadcrumbs');
        $breadcrumbs->addRouteItem('Home', 'homepage');
        $breadcrumbs->addItem('player.pointgameranking.full');

        return $this->render('VideoGamesRecordsCoreBundle:Ranking:player-points-game.html.twig', ['ranking' => $ranking]);
    }


    /**
     * @Route("/ranking-medals", name="vgr_player_ranking_medals")
     * @Method("GET")
     * @Cache(smaxage="10")
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function rankingMedalsAction()
    {
        $ranking = $this->getDoctrine()->getRepository('VideoGamesRecordsCoreBundle:Player')->getRankingMedals($this->getIdPlayer());
        $breadcrumbs = $this->get('white_october_breadcrumbs');
        $breadcrumbs->addRouteItem('Home', 'homepage');
        $breadcrumbs->addItem('player.medalranking.full');

        return $this->render('VideoGamesRecordsCoreBundle:Ranking:player-medals.html.twig', ['ranking' => $ranking]);
    }

    /**
     * @Route("/ranking-cups", name="vgr_player_ranking_cup")
     * @Method("GET")
     * @Cache(smaxage="10")
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function rankingCupsAction()
    {
        $ranking = $this->getDoctrine()->getRepository('VideoGamesRecordsCoreBundle:Player')->getRankingCups($this->getIdPlayer());
        $breadcrumbs = $this->get('white_october_breadcrumbs');
        $breadcrumbs->addRouteItem('Home', 'homepage');
        $breadcrumbs->addItem('player.cupranking.full');

        return $this->render('VideoGamesRecordsCoreBundle:Ranking:player-cups.html.twig', ['ranking' => $ranking]);
    }
}
