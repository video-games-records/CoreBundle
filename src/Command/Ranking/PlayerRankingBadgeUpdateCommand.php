<?php

declare(strict_types=1);

namespace VideoGamesRecords\CoreBundle\Command\Ranking;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use VideoGamesRecords\CoreBundle\Contracts\Ranking\RankUpdateInterface;
use VideoGamesRecords\CoreBundle\Ranking\Command\RankUpdate\PlayerRankUpdateHandler;

class PlayerRankingBadgeUpdateCommand extends Command
{
    protected static $defaultName = 'vgr-core:player-ranking-badge-update';

    private RankUpdateInterface $rankUpdate;

    public function __construct(
        #[Autowire(service: PlayerRankUpdateHandler::class)]
        RankUpdateInterface $rankUpdate
    ) {
        $this->rankUpdate = $rankUpdate;
        parent::__construct();
    }
    protected function configure(): void
    {
        $this
            ->setName('vgr-core:player-ranking-badge-update')
            ->setDescription('Command to update players ranking')
        ;
        parent::configure();
    }


    /**
     * @param InputInterface  $input
     * @param OutputInterface $output
     * @return int
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->rankUpdate->majRankBadge();
        return Command::SUCCESS;
    }
}
