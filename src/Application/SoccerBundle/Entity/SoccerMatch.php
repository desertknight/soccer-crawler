<?php

namespace Application\SoccerBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * SoccerMatch
 *
 * @ORM\Table(name="matches")
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks
 */
class SoccerMatch
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
    protected $matchId;
    
    /**
     *
     * @var integer
     * 
     * @ORM\Column(type="integer", nullable=true)
     */
    protected $fixtureId;
    
    /**
     *
     * @var League
     * 
     * @ORM\ManyToOne(targetEntity="League", inversedBy="matches", cascade={"persist"})
     * @ORM\JoinColumn(name="league_id", nullable=true)
     */
    protected $league;
    
    /**
     *
     * @var Team
     * 
     * @ORM\ManyToOne(targetEntity="Team", inversedBy="homeTeams", cascade={"persist"})
     * @ORM\JoinColumn(name="home_team_id", nullable=true)
     */
    protected $homeTeam;
    
    /**
     *
     * @var integer
     * 
     * @ORM\Column(type="integer", nullable=true)
     */
    protected $homeHalfTime = 0;
    
    /**
     *
     * @var integer
     * 
     * @ORM\Column(type="integer", nullable=true)
     */
    protected $homeFullTime = 0;
    
    /**
     *
     * @var Team
     * 
     * @ORM\ManyToOne(targetEntity="Team", inversedBy="awayTeams", cascade={"persist"})
     * @ORM\JoinColumn(name="away_team_id", nullable=true)
     */
    protected $awayTeam;
    
    /**
     *
     * @var integer
     * 
     * @ORM\Column(type="integer", nullable=true)
     */
    protected $awayHalfTime = 0;
    
    /**
     *
     * @var integer
     * 
     * @ORM\Column(type="integer", nullable=true)
     */
    protected $awayFullTime = 0;
    
    /**
     *
     * @var \DateTime
     * 
     * @ORM\Column(type="datetime", nullable=true)
     */
    protected $matchTime;
    
    /**
     *
     * @var array
     * 
     * @ORM\Column(type="array", nullable=true)
     */
    protected $information;
    
    /**
     *
     * @var boolean
     * 
     * @ORM\Column(type="boolean", nullable=true)
     */
    protected $isSample = TRUE;
    
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
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set matchId
     *
     * @param integer $matchId
     * @return SoccerMatch
     */
    public function setMatchId($matchId)
    {
        $this->matchId = $matchId;

        return $this;
    }

    /**
     * Get matchId
     *
     * @return integer 
     */
    public function getMatchId()
    {
        return $this->matchId;
    }

    /**
     * Set fixtureId
     *
     * @param integer $fixtureId
     * @return SoccerMatch
     */
    public function setFixtureId($fixtureId)
    {
        $this->fixtureId = $fixtureId;

        return $this;
    }

    /**
     * Get fixtureId
     *
     * @return integer 
     */
    public function getFixtureId()
    {
        return $this->fixtureId;
    }

    /**
     * Set homeHalfTime
     *
     * @param integer $homeHalfTime
     * @return SoccerMatch
     */
    public function setHomeHalfTime($homeHalfTime)
    {
        $this->homeHalfTime = $homeHalfTime;

        return $this;
    }

    /**
     * Get homeHalfTime
     *
     * @return integer 
     */
    public function getHomeHalfTime()
    {
        return $this->homeHalfTime;
    }

    /**
     * Set homeFullTime
     *
     * @param integer $homeFullTime
     * @return SoccerMatch
     */
    public function setHomeFullTime($homeFullTime)
    {
        $this->homeFullTime = $homeFullTime;

        return $this;
    }

    /**
     * Get homeFullTime
     *
     * @return integer 
     */
    public function getHomeFullTime()
    {
        return $this->homeFullTime;
    }

    /**
     * Set awayHalfTime
     *
     * @param integer $awayHalfTime
     * @return SoccerMatch
     */
    public function setAwayHalfTime($awayHalfTime)
    {
        $this->awayHalfTime = $awayHalfTime;

        return $this;
    }

    /**
     * Get awayHalfTime
     *
     * @return integer 
     */
    public function getAwayHalfTime()
    {
        return $this->awayHalfTime;
    }

    /**
     * Set awayFullTime
     *
     * @param integer $awayFullTime
     * @return SoccerMatch
     */
    public function setAwayFullTime($awayFullTime)
    {
        $this->awayFullTime = $awayFullTime;

        return $this;
    }

    /**
     * Get awayFullTime
     *
     * @return integer 
     */
    public function getAwayFullTime()
    {
        return $this->awayFullTime;
    }

    /**
     * Set matchTime
     *
     * @param \DateTime $matchTime
     * @return SoccerMatch
     */
    public function setMatchTime($matchTime)
    {
        $this->matchTime = $matchTime;

        return $this;
    }

    /**
     * Get matchTime
     *
     * @return \DateTime 
     */
    public function getMatchTime()
    {
        return $this->matchTime;
    }

    /**
     * Set information
     *
     * @param array $information
     * @return SoccerMatch
     */
    public function setInformation($information)
    {
        $this->information = $information;

        return $this;
    }

    /**
     * Get information
     *
     * @return array 
     */
    public function getInformation()
    {
        return $this->information;
    }

    /**
     * Set isSample
     *
     * @param boolean $isSample
     * @return SoccerMatch
     */
    public function setIsSample($isSample)
    {
        $this->isSample = $isSample;

        return $this;
    }

    /**
     * Get isSample
     *
     * @return boolean 
     */
    public function getIsSample()
    {
        return $this->isSample;
    }

    /**
     * Set createdTime
     *
     * @param \DateTime $createdTime
     * @return SoccerMatch
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
     * @return SoccerMatch
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
     * Set league
     *
     * @param \Application\SoccerBundle\Entity\League $league
     * @return SoccerMatch
     */
    public function setLeague(\Application\SoccerBundle\Entity\League $league = null)
    {
        $this->league = $league;

        return $this;
    }

    /**
     * Get league
     *
     * @return \Application\SoccerBundle\Entity\League 
     */
    public function getLeague()
    {
        return $this->league;
    }

    /**
     * Set homeTeam
     *
     * @param \Application\SoccerBundle\Entity\Team $homeTeam
     * @return SoccerMatch
     */
    public function setHomeTeam(\Application\SoccerBundle\Entity\Team $homeTeam = null)
    {
        $this->homeTeam = $homeTeam;

        return $this;
    }

    /**
     * Get homeTeam
     *
     * @return \Application\SoccerBundle\Entity\Team 
     */
    public function getHomeTeam()
    {
        return $this->homeTeam;
    }

    /**
     * Set awayTeam
     *
     * @param \Application\SoccerBundle\Entity\Team $awayTeam
     * @return SoccerMatch
     */
    public function setAwayTeam(\Application\SoccerBundle\Entity\Team $awayTeam = null)
    {
        $this->awayTeam = $awayTeam;

        return $this;
    }

    /**
     * Get awayTeam
     *
     * @return \Application\SoccerBundle\Entity\Team 
     */
    public function getAwayTeam()
    {
        return $this->awayTeam;
    }
}
