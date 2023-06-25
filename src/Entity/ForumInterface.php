<?php

namespace VideoGamesRecords\CoreBundle\Entity;

interface ForumInterface
{
    /** @return integer */
    public function getId(): int;
    /** @return string */
    public function getLibForum(): string;

    public function setLibForum(string $libForum): static;
}
