<?php

declare(strict_types=1);

namespace VideoGamesRecords\CoreBundle\Manager\Strategy\Badge;

use VideoGamesRecords\CoreBundle\Contracts\Strategy\BadgeTypeStrategyInterface;
use VideoGamesRecords\CoreBundle\Entity\Badge;

class SerieType extends AbstractBadgeStrategy implements BadgeTypeStrategyInterface
{
    /**
     * @param Badge $badge
     * @return bool
     */
    public function supports(Badge $badge): bool
    {
        return $badge->getType() === self::TYPE_SERIE;
    }

    /**
     * @param Badge $badge
     * @return string
     */
    public function getTitle(Badge $badge): string
    {
        return $badge->getSerie()->getName();
    }
}
