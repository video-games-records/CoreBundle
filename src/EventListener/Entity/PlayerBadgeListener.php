<?php

namespace VideoGamesRecords\CoreBundle\EventListener\Entity;

use Doctrine\ORM\Event\PreUpdateEventArgs;
use Doctrine\Persistence\Event\LifecycleEventArgs;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use VideoGamesRecords\CoreBundle\Entity\PlayerBadge;
use VideoGamesRecords\CoreBundle\VideoGamesRecordsCoreEvents;

class PlayerBadgeListener
{
    private array $changeSet = array();

    private EventDispatcherInterface $eventDispatcher;

    public function __construct(EventDispatcherInterface $eventDispatcher)
    {
        $this->eventDispatcher = $eventDispatcher;
    }

    /**
     * @param PlayerBadge        $playerBadge
     * @param PreUpdateEventArgs $event
     */
    public function preUpdate(PlayerBadge $playerBadge, PreUpdateEventArgs $event): void
    {
        $this->changeSet = $event->getEntityChangeSet();
    }

    /**
     * @param PlayerBadge        $playerBadge
     * @param LifecycleEventArgs $event
     */
    public function postUpdate(PlayerBadge $playerBadge, LifecycleEventArgs $event): void
    {
        if ($playerBadge->getBadge()->isTypeMaster() && array_key_exists('ended_at', $this->changeSet)) {
            $this->eventDispatcher->dispatch($event, VideoGamesRecordsCoreEvents::PLAYER_BADGE_LOST);
        }
    }
}
