<?php

namespace VideoGamesRecords\CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Knp\DoctrineBehaviors\Contract\Entity\SluggableInterface;
use Knp\DoctrineBehaviors\Model\Sluggable\SluggableTrait;

/**
 * Serie
 *
 * @ORM\Table(name="vgr_serie")
 * @ORM\Entity(repositoryClass="VideoGamesRecords\CoreBundle\Repository\SerieRepository")
 */
class Serie implements SluggableInterface
{
    use SluggableTrait;

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var string
     *
     * @Assert\Length(max="255")
     * @ORM\Column(name="libSerie", type="string", length=255, nullable=false)
     */
    private $libSerie;


    /**
     * @ORM\OneToMany(targetEntity="VideoGamesRecords\CoreBundle\Entity\Game", mappedBy="serie", cascade={"persist", "remove"}, orphanRemoval=true)
     */
    private $games;

    /**
     * @return string
     */
    public function __toString(): string
    {
        return sprintf('%s [%s]', $this->getDefaultName(), $this->id);
    }

    /**
     * @return string
     */
    public function getDefaultName(): string
    {
        return $this->libSerie;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->libSerie;
    }

      /**
     * @param string $libSerie
     * @return $this
     */
    public function setLibSerie(string $libSerie): Serie
    {
        $this->libSerie = $libSerie;
        return $this;
    }

    /**
     * @return string
     */
    public function getLibSerie(): ?string
    {
        return $this->libSerie;
    }

    /**
     * Set idSerie
     * @param integer $id
     * @return $this
     */
    public function setId(int $id): Serie
    {
        $this->id = $id;
        return $this;
    }

    /**
     * Get idSerie
     *
     * @return integer
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return mixed
     */
    public function getGames()
    {
        return $this->games;
    }

    /**
     * Returns an array of the fields used to generate the slug.
     *
     * @return string[]
     */
    public function getSluggableFields(): array
    {
        return ['defaultName'];
    }
}
