<?php

namespace VideoGamesRecords\CoreBundle\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\ORM\Mapping as ORM;
use Knp\DoctrineBehaviors\Contract\Entity\TranslatableInterface;
use Knp\DoctrineBehaviors\Model\Translatable\TranslatableMethodsTrait;
use Knp\DoctrineBehaviors\Model\Translatable\TranslatablePropertiesTrait;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Country
 *
 * @ORM\Table(name="vgr_country")
 * @ORM\Entity
 * @ApiResource(attributes={"order"={"translations.name"}})
 */
class Country implements TranslatableInterface
{
    use TranslatablePropertiesTrait;
    use TranslatableMethodsTrait;

    /**
     * @Assert\Length(min="2", max="2")
     * @ORM\Column(name="code_iso2", type="string", length=2, nullable=false)
     */
    private string $codeIso2;

    /**
     * @Assert\Length(min="3", max="3")
     * @ORM\Column(name="code_iso3", type="string", length=3, nullable=false)
     */
    private string $codeIso3;

    /**
     * @ORM\Column(name="code_iso_numeric", type="integer", nullable=false)
     */
    private int $codeIsoNumeric;

    /**
     * @ORM\Column(name="slug", type="string", length=100, nullable=true)
     */
    private string $slug;

    /**
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private ?int $id = null;

    /**
     * @ORM\OneToOne(targetEntity="VideoGamesRecords\CoreBundle\Entity\Badge", inversedBy="country")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="idBadge", referencedColumnName="id")
     * })
     */
    private ?Badge $badge;

    /**
     * @ORM\Column(name="boolMaj", type="boolean", nullable=false, options={"default":0})
     */
    private bool $boolMaj = false;

    /**
     * Set codeIso
     *
     * @param string $codeIso2
     * @return Country
     */
    public function setCodeIso2(string $codeIso2): Country
    {
        $this->codeIso2 = $codeIso2;

        return $this;
    }

    /**
     * Get codeIso
     *
     * @return string
     */
    public function getCodeIso2(): string
    {
        return $this->codeIso2;
    }

    /**
     * @return string
     */
    public function getCodeIso3(): string
    {
        return $this->codeIso3;
    }

    /**
     * @param string $codeIso3
     * @return Country
     */
    public function setCodeIso3(string $codeIso3): Country
    {
        $this->codeIso3 = $codeIso3;
        return $this;
    }

    /**
     * @return int
     */
    public function getCodeIsoNumeric(): int
    {
        return $this->codeIsoNumeric;
    }

    /**
     * @param int $codeIsoNumeric
     * @return Country
     */
    public function setCodeIsoNumeric(int $codeIsoNumeric): Country
    {
        $this->codeIsoNumeric = $codeIsoNumeric;
        return $this;
    }

    /**
     * @param string $name
     * @return $this
     */
    public function setName(string $name): Country
    {
        $this->translate(null, false)->setName($name);

        return $this;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->translate(null, false)->getName();
    }

    /**
     * Get id
     *
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * Set id
     *
     * @param int $id
     * @return $this
     */
    public function setId(int $id): Country
    {
        $this->id = $id;

        return $this;
    }

    /**
     * set badge
     * @param Badge|null $badge
     * @return $this
     */
    public function setBadge(Badge $badge = null): Country
    {
        $this->badge = $badge;

        return $this;
    }

    /**
     * Get idBadge
     * @return Badge|null
     */
    public function getBadge(): ?Badge
    {
        return $this->badge;
    }

    /**
     * Set boolMaj
     *
     * @param boolean $boolMaj
     * @return $this
     */
    public function setBoolMaj(bool $boolMaj): Country
    {
        $this->boolMaj = $boolMaj;

        return $this;
    }

    /**
     * Get boolMaj
     *
     * @return bool
     */
    public function getBoolMaj(): bool
    {
        return $this->boolMaj;
    }

    public function getSlug(): string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): void
    {
        $this->slug = $slug;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return sprintf('%s [%d]', $this->getDefaultName(), $this->getId());
    }

    /**
     * @return string
     */
    public function getDefaultName(): string
    {
        return $this->translate('en', false)->getName();
    }
}
