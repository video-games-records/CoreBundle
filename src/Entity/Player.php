<?php

namespace VideoGamesRecords\CoreBundle\Entity;

use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\OrderFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;
use ApiPlatform\Core\Serializer\Filter\GroupFilter;
use DateTime;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Knp\DoctrineBehaviors\Contract\Entity\SluggableInterface;
use Knp\DoctrineBehaviors\Model\Sluggable\SluggableTrait;
use Symfony\Component\Validator\Constraints as Assert;
use VideoGamesRecords\CoreBundle\Entity\User\UserInterface;

/**
 * Player
 *
 * @ORM\Table(name="vgr_player")
 * @ORM\Entity(repositoryClass="VideoGamesRecords\CoreBundle\Repository\PlayerRepository")
 * @ApiResource(attributes={"order"={"pseudo"}})
 * @ApiFilter(
 *     SearchFilter::class,
 *     properties={
 *          "pseudo": "partial",
 *          "user.enabled": "exact",
 *      }
 * )
 * @ApiFilter(
 *     GroupFilter::class,
 *     arguments={
 *          "parameterName": "groups",
 *          "overrideDefaultGroups": true,
 *          "whitelist": {
 *              "player.read",
 *              "player.team",
 *              "player.country",
 *              "country.read",
 *              "player.pointChart",
 *              "player.medal",
 *              "player.user",
 *              "vgr.user.read",
 *              "team.read.mini",
 *              "user.status.read",
 *          }
 *     }
 * )
 * @ApiFilter(
 *     OrderFilter::class,
 *     properties={
 *          "id":"ASC",
 *          "pseudo" : "ASC",
 *          "user.createdAt": "DESC",
 *          "user.nbConnexion": "DESC",
 *          "user.lastLogin": "DESC",
 *          "user.nbForumMessage": "DESC"
 *     },
 *     arguments={"orderParameterName"="order"}
 * )
 */
class Player implements SluggableInterface
{
    use SluggableTrait;

    /**
     * @ORM\OneToOne(targetEntity="VideoGamesRecords\CoreBundle\Entity\User\UserInterface")
     * @ORM\JoinColumn(name="normandie_user_id", referencedColumnName="id")
     */
    private $user;

    /**
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private ?int $id = null;

    /**
     * @Assert\Length(min="3",max="50")
     * @ORM\Column(name="pseudo", type="string", length=50, nullable=false, unique=true)
     */
    private string $pseudo;

    /**
     * @ORM\Column(name="avatar", type="string", length=100, nullable=false)
     */
    private string $avatar = 'default.jpg';

    /**

     * @ORM\Column(name="gamerCard", type="string", length=50, nullable=true)
     */
    private ?string $gamerCard;

    /**
     * @ORM\Column(name="chartRank0", type="integer", nullable=true)
     */
    private int $chartRank0 = 0;

    /**
     * @ORM\Column(name="chartRank1", type="integer", nullable=true)
     */
    private int $chartRank1 = 0;

    /**
     * @ORM\Column(name="chartRank2", type="integer", nullable=true)
     */
    private int $chartRank2 = 0;

    /**
     * @ORM\Column(name="chartRank3", type="integer", nullable=true)
     */
    private int $chartRank3 = 0;

    /**
     * @ORM\Column(name="pointChart", type="integer", nullable=false)
     */
    private int $pointChart = 0;

    /**
     * @ORM\Column(name="pointVGR", type="integer", nullable=false)
     */
    private int $pointVGR = 0;

    /**
     * @ORM\Column(name="pointBadge", type="integer", nullable=false)
     */
    private int $pointBadge = 0;

    /**
     * @ORM\Column(name="presentation", type="text", length=65535, nullable=true)
     */
    private ?string $presentation;

    /**
     * @ORM\Column(name="collection", type="text", length=65535, nullable=true)
     */
    private ?string $collection;

    /**
     * @ORM\Column(name="rankPointChart", type="integer", nullable=true)
     */
    private ?int $rankPointChart;

    /**
     * @ORM\Column(name="rankMedal", type="integer", nullable=true)
     */
    private ?int $rankMedal;

    /**
     * @ORM\Column(name="rankProof", type="integer", nullable=true)
     */
    private ?int $rankProof;

    /**
     * @ORM\Column(name="rankBadge", type="integer", nullable=true)
     */
    private ?int $rankBadge;

    /**
     * @ORM\Column(name="rankCup", type="integer", nullable=true)
     */
    private ?int $rankCup;

    /**
     * @ORM\Column(name="rankCountry", type="integer", nullable=true)
     */
    private ?int $rankCountry;

    /**
     * @ORM\Column(name="gameRank0", type="integer", nullable=true)
     */
    private int $gameRank0 = 0;

    /**
     * @ORM\Column(name="gameRank1", type="integer", nullable=true)
     */
    private int $gameRank1 = 0;

    /**
     * @ORM\Column(name="gameRank2", type="integer", nullable=true)
     */
    private int $gameRank2 = 0;

    /**
     * @ORM\Column(name="gameRank3", type="integer", nullable=true)
     */
    private int $gameRank3 = 0;

    /**
     * @ORM\Column(name="nbGame", type="integer", nullable=false)
     */
    private int $nbGame = 0;

    /**
     * @ORM\Column(name="nbChart", type="integer", nullable=false)
     */
    private int $nbChart = 0;

    /**
     * @ORM\Column(name="nbChartProven", type="integer", nullable=false)
     */
    private int $nbChartProven = 0;

    /**
     * @ORM\Column(name="nbChartDisabled", type="integer", nullable=false)
     */
    private int $nbChartDisabled = 0;

    /**
     * @ORM\Column(name="nbMasterBadge", type="integer", nullable=false)
     */
    private int $nbMasterBadge = 0;

    /**
     * @ORM\Column(name="pointGame", type="integer", nullable=false)
     */
    private int $pointGame = 0;

    /**
     * @ORM\Column(name="birthDate", type="date", nullable=true)
     */
    protected ?DateTime $birthDate;

    /**
     * @ORM\Column(name="website", type="string", length=255, nullable=true)
     */
    protected ?string $website;

    /**
     * @ORM\Column(name="youtube", type="string", length=255, nullable=true)
     */
    protected ?string $youtube;

    /**
     * @ORM\Column(name="twitch", type="string", length=255, nullable=true)
     */
    protected ?string $twitch;

    /**
     * @ORM\Column(name="gender", type="string", length=1, nullable=false)
     */
    protected string $gender = 'I';

    /**
     * @ORM\Column(name="displayPersonalInfos", type="boolean", nullable=false)
     */
    private bool $displayPersonalInfos = false;

    /**
     * @ORM\Column(name="rankPointGame", type="integer", nullable=true)
     */
    private ?int $rankPointGame;

    /**
     * @ORM\OneToMany(targetEntity="VideoGamesRecords\CoreBundle\Entity\PlayerPlatform", mappedBy="player")
     */
    private $playerPlatform;

    /**
     * @ORM\OneToMany(targetEntity="VideoGamesRecords\CoreBundle\Entity\PlayerGame", mappedBy="player")
     */
    private $playerGame;

    /**
     * @ORM\OneToMany(targetEntity="VideoGamesRecords\CoreBundle\Entity\PlayerGroup", mappedBy="player")
     */
    private $playerGroup;

    /**
     * @ORM\OneToMany(targetEntity="VideoGamesRecords\CoreBundle\Entity\PlayerBadge", mappedBy="player")
     */
    private $playerBadge;

    /**
     * @ORM\OneToMany(targetEntity="VideoGamesRecords\CoreBundle\Entity\LostPosition", mappedBy="player")
     */
    private $lostPositions;

    /**
     * @ORM\Column(name="boolMaj", type="boolean", nullable=false, options={"default":0})
     */
    private $boolMaj = false;

    /**
     * @var Team
     *
     * @ORM\ManyToOne(targetEntity="VideoGamesRecords\CoreBundle\Entity\Team", inversedBy="players")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="idTeam", referencedColumnName="id")
     * })
     */
    private $team;

    /**
     * @var Country
     *
     * @ORM\ManyToOne(targetEntity="VideoGamesRecords\CoreBundle\Entity\Country")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="idCountry", referencedColumnName="id")
     * })
     */
    protected $country;


    /**
     * @ORM\OneToMany(targetEntity="VideoGamesRecords\CoreBundle\Entity\Proof", mappedBy="player")
     */
    private $proofs;

    /**
     * @ORM\OneToMany(targetEntity="VideoGamesRecords\CoreBundle\Entity\Proof", mappedBy="playerResponding")
     */
    private $proofRespondings;

    /**
     * @ORM\OneToMany(targetEntity="VideoGamesRecords\CoreBundle\Entity\Rule", mappedBy="player")
     */
    private $rules;

    /**
     * @var DateTime
     * @ORM\Column(name="lastDisplayLostPosition", type="datetime", nullable=true)
     */
    protected $lastDisplayLostPosition;

    /**
     * @return string
     */
    public function __toString()
    {
        return sprintf('%s [%s]', $this->getPseudo(), $this->getId());
    }

    /**
     * Set id
     *
     * @param integer $id
     * @return $this
     */
    public function setId(int $id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set pseudo
     *
     * @param string $pseudo
     * @return $this
     */
    public function setPseudo(string $pseudo)
    {
        $this->pseudo = $pseudo;

        return $this;
    }

    /**
     * Get pseudo
     *
     * @return string
     */
    public function getPseudo()
    {
        return $this->pseudo;
    }

    /**
     * Set avatar
     *
     * @param string $avatar
     * @return $this
     */
    public function setAvatar(string $avatar)
    {
        $this->avatar = $avatar;

        return $this;
    }

    /**
     * Get avatar
     *
     * @return string
     */
    public function getAvatar()
    {
        return $this->avatar;
    }

    /**
     * Set gamerCard
     *
     * @param string $gamerCard
     * @return $this
     */
    public function setGamerCard(string $gamerCard)
    {
        $this->gamerCard = $gamerCard;

        return $this;
    }

    /**
     * Get gamerCard
     *
     * @return string
     */
    public function getGamerCard()
    {
        return $this->gamerCard;
    }

    /**
     * Set chartRank0
     *
     * @param integer $chartRank0
     * @return $this
     */
    public function setChartRank0(int $chartRank0)
    {
        $this->chartRank0 = $chartRank0;

        return $this;
    }

    /**
     * Get chartRank0
     *
     * @return integer
     */
    public function getChartRank0()
    {
        return $this->chartRank0;
    }

    /**
     * Set chartRank1
     *
     * @param integer $chartRank1
     * @return $this
     */
    public function setChartRank1(int $chartRank1)
    {
        $this->chartRank1 = $chartRank1;

        return $this;
    }

    /**
     * Get chartRank1
     *
     * @return integer
     */
    public function getChartRank1()
    {
        return $this->chartRank1;
    }

    /**
     * Set chartRank2
     *
     * @param integer $chartRank2
     * @return $this
     */
    public function setChartRank2(int $chartRank2)
    {
        $this->chartRank2 = $chartRank2;

        return $this;
    }

    /**
     * Get chartRank2
     *
     * @return integer
     */
    public function getChartRank2()
    {
        return $this->chartRank2;
    }

    /**
     * Set chartRank3
     *
     * @param integer $chartRank3
     * @return $this
     */
    public function setChartRank3(int $chartRank3)
    {
        $this->chartRank3 = $chartRank3;

        return $this;
    }

    /**
     * Get chartRank3
     *
     * @return integer
     */
    public function getChartRank3()
    {
        return $this->chartRank3;
    }

    /**
     * Set pointChart
     *
     * @param integer $pointChart
     * @return $this
     */
    public function setPointChart(int $pointChart)
    {
        $this->pointChart = $pointChart;

        return $this;
    }

    /**
     * Get pointChart
     *
     * @return integer
     */
    public function getPointChart()
    {
        return $this->pointChart;
    }

    /**
     * Set pointVGR
     *
     * @param integer $pointVGR
     * @return $this
     */
    public function setPointVGR(int $pointVGR)
    {
        $this->pointVGR = $pointVGR;

        return $this;
    }

    /**
     * Get pointVGR
     *
     * @return integer
     */
    public function getPointVGR()
    {
        return $this->pointVGR;
    }

    /**
     * Set pointBadge
     *
     * @param integer $pointBadge
     * @return $this
     */
    public function setPointBadge(int $pointBadge)
    {
        $this->pointBadge = $pointBadge;

        return $this;
    }

    /**
     * Get pointBadge
     *
     * @return integer
     */
    public function getPointBadge()
    {
        return $this->pointBadge;
    }

    /**
     * Set presentation
     *
     * @param string|null $presentation
     * @return $this
     */
    public function setPresentation(string $presentation = null)
    {
        $this->presentation = $presentation;

        return $this;
    }

    /**
     * Get presentation
     *
     * @return string
     */
    public function getPresentation()
    {
        return $this->presentation;
    }

    /**
     * Set collection
     *
     * @param string|null $collection
     * @return Player
     */
    public function setCollection(string $collection = null)
    {
        $this->collection = $collection;

        return $this;
    }

    /**
     * Get collection
     *
     * @return string
     */
    public function getCollection()
    {
        return $this->collection;
    }

    /**
     * Set rankPointChart
     *
     * @param integer $rankPointChart
     * @return Player
     */
    public function setRankPointChart(int $rankPointChart)
    {
        $this->rankPointChart = $rankPointChart;

        return $this;
    }

    /**
     * Get rankPointChart
     *
     * @return integer
     */
    public function getRankPointChart()
    {
        return $this->rankPointChart;
    }

    /**
     * Set rankMedal
     *
     * @param integer $rankMedal
     * @return Player
     */
    public function setRankMedal(int $rankMedal)
    {
        $this->rankMedal = $rankMedal;

        return $this;
    }

    /**
     * Get rankMedal
     *
     * @return integer
     */
    public function getRankMedal()
    {
        return $this->rankMedal;
    }

    /**
     * Set rankProof
     *
     * @param integer $rankProof
     * @return Player
     */
    public function setRankProof(int $rankProof)
    {
        $this->rankProof = $rankProof;

        return $this;
    }

    /**
     * Get rankProof
     *
     * @return integer
     */
    public function getRankProof()
    {
        return $this->rankProof;
    }

    /**
     * Set rankBadge
     *
     * @param integer $rankBadge
     * @return Player
     */
    public function setRankBadge(int $rankBadge)
    {
        $this->rankBadge = $rankBadge;

        return $this;
    }

    /**
     * Get rankBadge
     *
     * @return integer
     */
    public function getRankBadge()
    {
        return $this->rankBadge;
    }

    /**
     * Set rankCup
     *
     * @param integer $rankCup
     * @return Player
     */
    public function setRankCup(int $rankCup)
    {
        $this->rankCup = $rankCup;

        return $this;
    }

    /**
     * Get rankCup
     *
     * @return integer
     */
    public function getRankCup()
    {
        return $this->rankCup;
    }

    /**
     * Set rankCountry
     *
     * @param integer $rankCountry
     * @return Player
     */
    public function setRankCountry(int $rankCountry)
    {
        $this->rankCountry = $rankCountry;

        return $this;
    }

    /**
     * Get rankCountry
     *
     * @return integer
     */
    public function getRankCountry()
    {
        return $this->rankCountry;
    }

    /**
     * Set gameRank0
     *
     * @param integer $gameRank0
     * @return Player
     */
    public function setGameRank0(int $gameRank0)
    {
        $this->gameRank0 = $gameRank0;

        return $this;
    }

    /**
     * Get gameRank0
     *
     * @return integer
     */
    public function getgameRank0()
    {
        return $this->gameRank0;
    }

    /**
     * Set gameRank1
     *
     * @param integer $gameRank1
     * @return Player
     */
    public function setGameRank1(int $gameRank1)
    {
        $this->gameRank1 = $gameRank1;

        return $this;
    }

    /**
     * Get gameRank1
     *
     * @return integer
     */
    public function getGameRank1()
    {
        return $this->gameRank1;
    }

    /**
     * Set gameRank2
     *
     * @param integer $gameRank2
     * @return Player
     */
    public function setGameRank2(int $gameRank2)
    {
        $this->gameRank2 = $gameRank2;

        return $this;
    }

    /**
     * Get gameRank2
     *
     * @return integer
     */
    public function getGameRank2()
    {
        return $this->gameRank2;
    }

    /**
     * Set gameRank3
     *
     * @param integer $gameRank3
     * @return Player
     */
    public function setGameRank3(int $gameRank3)
    {
        $this->gameRank3 = $gameRank3;

        return $this;
    }

    /**
     * Get gameRank3
     *
     * @return integer
     */
    public function getGameRank3()
    {
        return $this->gameRank3;
    }

    /**
     * Set nbGame
     *
     * @param integer $nbGame
     * @return Player
     */
    public function setNbGame(int $nbGame)
    {
        $this->nbGame = $nbGame;

        return $this;
    }

    /**
     * Get nbGame
     *
     * @return integer
     */
    public function getNbGame()
    {
        return $this->nbGame;
    }

    /**
     * Set nbChart
     *
     * @param integer $nbChart
     * @return Player
     */
    public function setNbChart(int $nbChart)
    {
        $this->nbChart = $nbChart;

        return $this;
    }

    /**
     * Get nbChart
     *
     * @return integer
     */
    public function getNbChart()
    {
        return $this->nbChart;
    }

    /**
     * Set nbChartProven
     *
     * @param integer $nbChartProven
     * @return Player
     */
    public function setNbChartProven(int $nbChartProven)
    {
        $this->nbChartProven = $nbChartProven;

        return $this;
    }

    /**
     * Get nbChartProven
     *
     * @return integer
     */
    public function getNbChartProven()
    {
        return $this->nbChartProven;
    }

    /**
     * Set nbChartDisabled
     *
     * @param integer $nbChartDisabled
     * @return Player
     */
    public function setNbChartDisabled(int $nbChartDisabled)
    {
        $this->nbChartDisabled = $nbChartDisabled;

        return $this;
    }

    /**
     * Get nbChartDisabled
     *
     * @return integer
     */
    public function getNbChartDisabled()
    {
        return $this->nbChartDisabled;
    }

    /**
     * Set nbMasterBadge
     *
     * @param integer $nbMasterBadge
     * @return Player
     */
    public function setNbMasterBadge(int $nbMasterBadge)
    {
        $this->nbMasterBadge = $nbMasterBadge;

        return $this;
    }

    /**
     * Get nbMasterBadge
     *
     * @return integer
     */
    public function getNbMasterBadge()
    {
        return $this->nbMasterBadge;
    }

    /**
     * Set pointGame
     *
     * @param integer $pointGame
     * @return Player
     */
    public function setPointGame(int $pointGame)
    {
        $this->pointGame = $pointGame;

        return $this;
    }

    /**
     * Get pointGame
     *
     * @return integer
     */
    public function getPointGame()
    {
        return $this->pointGame;
    }

    /**
     * Set rankPointGame
     *
     * @param integer $rankPointGame
     * @return Player
     */
    public function setRankPointGame(int $rankPointGame)
    {
        $this->rankPointGame = $rankPointGame;

        return $this;
    }

    /**
     * Get rankPointGame
     *
     * @return integer
     */
    public function getRankPointGame()
    {
        return $this->rankPointGame;
    }

    /**
     * @return string
     */
    public function getWebsite()
    {
        return $this->website;
    }

    /**
     * @param string|null $website
     * @return $this
     */
    public function setWebsite(string $website = null)
    {
        $this->website = $website;
        return $this;
    }

    /**
     * @return string
     */
    public function getYoutube()
    {
        return $this->youtube;
    }

    /**
     * @param string|null $youtube
     * @return $this
     */
    public function setYoutube(string $youtube = null)
    {
        $this->youtube = $youtube;
        return $this;
    }

    /**
     * @return string
     */
    public function getTwitch()
    {
        return $this->twitch;
    }

    /**
     * @param string|null $twitch
     * @return $this
     */
    public function setTwitch(string $twitch = null)
    {
        $this->twitch = $twitch;
        return $this;
    }

    /**
     * @return DateTime
     */
    public function getBirthDate()
    {
        return $this->birthDate;
    }


    /**
     * @param DateTime|null $birthDate
     * @return $this
     */
    public function setBirthDate(DateTime $birthDate = null)
    {
        $this->birthDate = $birthDate;
        return $this;
    }

    /**
     * @return string
     */
    public function getGender()
    {
        return $this->gender;
    }

    /**
     * @param string $gender
     * @return $this
     */
    public function setGender(string $gender)
    {
        $this->gender = $gender;
        return $this;
    }

    /**
     * Set displayPersonalInfos
     * @param bool $displayPersonalInfos
     * @return $this
     */
    public function setDisplayPersonalInfos(bool $displayPersonalInfos)
    {
        $this->displayPersonalInfos = $displayPersonalInfos;

        return $this;
    }

    /**
     * Get DisplayPersonalInfos
     * @return bool
     */
    public function getDisplayPersonalInfos()
    {
        return $this->displayPersonalInfos;
    }

    /**
     * @return UserInterface
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @param $user
     * @return Player
     */
    public function setUser($user)
    {
        $this->user = $user;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getPlayerPlatform()
    {
        return $this->playerPlatform;
    }

    /**
     * @return mixed
     */
    public function getPlayerGame()
    {
        return $this->playerGame;
    }

    /**
     * @return mixed
     */
    public function getPlayerBadge()
    {
        return $this->playerBadge;
    }

    /**
     * Set Team
     * @param Team|null $team
     * @return $this
     */
    public function setTeam(Team $team = null)
    {
        $this->team = $team;

        return $this;
    }

    /**
     * Get team
     * @return Team
     */
    public function getTeam()
    {
        return $this->team;
    }

    /**
     * @return Player
     */
    public function getPlayer()
    {
        return $this;
    }

    /**
     * @return Country
     */
    public function getCountry()
    {
        return $this->country;
    }

    /**
     * @param $country
     * @return Player
     */
    public function setCountry($country)
    {
        $this->country = $country;
        return $this;
    }

    /**
     * @return Collection
     */
    public function getLostPositions(): Collection
    {
        return $this->lostPositions;
    }

    /**
     * @return DateTime
     */
    public function getLastDisplayLostPosition(): ?DateTime
    {
        return $this->lastDisplayLostPosition;
    }


    /**
     * @param DateTime|null $lastDisplayLostPosition
     * @return $this
     */
    public function setLastDisplayLostPosition(DateTime $lastDisplayLostPosition = null)
    {
        $this->lastDisplayLostPosition = $lastDisplayLostPosition;
        return $this;
    }

    /**
     * Set boolMaj
     *
     * @param bool $boolMaj
     * @return $this
     */
    public function setBoolMaj(bool $boolMaj)
    {
        $this->boolMaj = $boolMaj;

        return $this;
    }

    /**
     * Get boolMaj
     *
     * @return bool
     */
    public function getBoolMaj()
    {
        return $this->boolMaj;
    }

    /**
     * Returns an array of the fields used to generate the slug.
     *
     * @return string[]
     */
    public function getSluggableFields(): array
    {
        return ['pseudo'];
    }

    /**
     * @return bool
     */
    public function isLeader()
    {
        return ($this->getTeam() !== null) && ($this->getTeam()->getLeader()->getId() === $this->getId());
    }

    /**
     * @return string
     */
    public function getUrl()
    {
        return sprintf(
            '%s-player-p%d/index',
            $this->getSlug(),
            $this->getId()
        );
    }
}
