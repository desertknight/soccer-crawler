<?php

namespace Application\SoccerBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * League
 *
 * @ORM\Table(name="leagues")
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks()
 */
class League
{

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     *
     * @var integer
     * 
     * @ORM\Column(type="integer")
     */
    protected $leagueId;
    
    /**
     *
     * @var Country
     * 
     * @ORM\ManyToOne(targetEntity="Country", inversedBy="leagues", cascade={"persist"})
     */
    protected $country;
    
    /**
     *
     * @var SoccerMatch
     * 
     * @ORM\OneToMany(targetEntity="SoccerMatch", mappedBy="league", orphanRemoval=true, cascade={"persist"}) 
     */
    protected $matches;
    
    /**
     *
     * @var string
     * 
     * @ORM\Column(type="string", length=255)
     */
    protected $name;
    
    /**
     *
     * @var boolean
     * 
     * @ORM\Column(type="boolean", nullable=true)
     */
    protected $hasHistory = TRUE;
    
    /**
     *
     * @var boolean
     * 
     * @ORM\Column(type="boolean", nullable=true)
     */
    protected $hasFixture = TRUE;
    
    /**
     *
     * @var boolean
     * 
     * @ORM\Column(type="boolean", nullable=true)
     */
    protected $hasLiveScore = TRUE;
    
    /**
     *
     * @var integer
     * 
     * @ORM\Column(type="integer", nullable=true)
     */
    protected $countMatches = 0;
    
    /**
     *
     * @var \DateTime
     * 
     * @ORM\Column(type="datetime", nullable=true)
     */
    protected $latestMatch;
    
    /**
     *
     * @var boolean
     * 
     * @ORM\Column(type="boolean", nullable=true)
     */
    protected $historyUpdate = FALSE;
    
    /**
     *
     * @var \DateTime
     * 
     * @ORM\Column(type="datetime", nullable=true, name="created_time")
     */
    protected $createdTime;
    
    /**
     *
     * @var \DateTime
     * 
     * @ORM\Column(type="datetime", nullable=true, name="updated_time")
     */
    protected $updatedTime;

    /**
     * @ORM\PreUpdate
     * @ORM\PrePersist
     */
    public function updateModifiedTime()
    {
        $this->updatedTime = new \DateTime;
        if ($this->getCreatedTime() === NULL) {
            $this->createdTime = new \DateTime;
        }
    }

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->matches = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set leagueId
     *
     * @param integer $leagueId
     * @return League
     */
    public function setLeagueId($leagueId)
    {
        $this->leagueId = $leagueId;

        return $this;
    }

    /**
     * Get leagueId
     *
     * @return integer 
     */
    public function getLeagueId()
    {
        return $this->leagueId;
    }

    /**
     * Set name
     *
     * @param string $name
     * @return League
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string 
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set hasHistory
     *
     * @param boolean $hasHistory
     * @return League
     */
    public function setHasHistory($hasHistory)
    {
        $this->hasHistory = $hasHistory;

        return $this;
    }

    /**
     * Get hasHistory
     *
     * @return boolean 
     */
    public function getHasHistory()
    {
        return $this->hasHistory;
    }

    /**
     * Set hasFixture
     *
     * @param boolean $hasFixture
     * @return League
     */
    public function setHasFixture($hasFixture)
    {
        $this->hasFixture = $hasFixture;

        return $this;
    }

    /**
     * Get hasFixture
     *
     * @return boolean 
     */
    public function getHasFixture()
    {
        return $this->hasFixture;
    }

    /**
     * Set hasLiveScore
     *
     * @param boolean $hasLiveScore
     * @return League
     */
    public function setHasLiveScore($hasLiveScore)
    {
        $this->hasLiveScore = $hasLiveScore;

        return $this;
    }

    /**
     * Get hasLiveScore
     *
     * @return boolean 
     */
    public function getHasLiveScore()
    {
        return $this->hasLiveScore;
    }

    /**
     * Set countMatches
     *
     * @param integer $countMatches
     * @return League
     */
    public function setCountMatches($countMatches)
    {
        $this->countMatches = $countMatches;

        return $this;
    }

    /**
     * Get countMatches
     *
     * @return integer 
     */
    public function getCountMatches()
    {
        return $this->countMatches;
    }

    /**
     * Set latestMatch
     *
     * @param \DateTime $latestMatch
     * @return League
     */
    public function setLatestMatch($latestMatch)
    {
        $this->latestMatch = $latestMatch;

        return $this;
    }

    /**
     * Get latestMatch
     *
     * @return \DateTime 
     */
    public function getLatestMatch()
    {
        return $this->latestMatch;
    }

    /**
     * Set historyUpdate
     *
     * @param boolean $historyUpdate
     * @return League
     */
    public function setHistoryUpdate($historyUpdate)
    {
        $this->historyUpdate = $historyUpdate;

        return $this;
    }

    /**
     * Get historyUpdate
     *
     * @return boolean 
     */
    public function getHistoryUpdate()
    {
        return $this->historyUpdate;
    }

    /**
     * Set createdTime
     *
     * @param \DateTime $createdTime
     * @return League
     */
    public function setCreatedTime($createdTime)
    {
        $this->createdTime = $createdTime;

        return $this;
    }

    /**
     * Get createdTime
     *
     * @return \DateTime 
     */
    public function getCreatedTime()
    {
        return $this->createdTime;
    }

    /**
     * Set updatedTime
     *
     * @param \DateTime $updatedTime
     * @return League
     */
    public function setUpdatedTime($updatedTime)
    {
        $this->updatedTime = $updatedTime;

        return $this;
    }

    /**
     * Get updatedTime
     *
     * @return \DateTime 
     */
    public function getUpdatedTime()
    {
        return $this->updatedTime;
    }

    /**
     * Set country
     *
     * @param \Application\SoccerBundle\Entity\Country $country
     * @return League
     */
    public function setCountry(\Application\SoccerBundle\Entity\Country $country = null)
    {
        $this->country = $country;

        return $this;
    }

    /**
     * Get country
     *
     * @return \Application\SoccerBundle\Entity\Country 
     */
    public function getCountry()
    {
        return $this->country;
    }

    /**
     * Add matches
     *
     * @param \Application\SoccerBundle\Entity\SoccerMatch $matches
     * @return League
     */
    public function addMatch(\Application\SoccerBundle\Entity\SoccerMatch $matches)
    {
        $this->matches[] = $matches;

        return $this;
    }

    /**
     * Remove matches
     *
     * @param \Application\SoccerBundle\Entity\SoccerMatch $matches
     */
    public function removeMatch(\Application\SoccerBundle\Entity\SoccerMatch $matches)
    {
        $this->matches->removeElement($matches);
    }

    /**
     * Get matches
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getMatches()
    {
        return $this->matches;
    }
}
