<?php

declare(strict_types=1);

namespace VideoGamesRecords\CoreBundle\Entity;

interface UserInterface
{
    /** @return integer */
    public function getId();
    /** @return string */
    public function getUsername();
    /** @return string */
    public function getLocale();
}
