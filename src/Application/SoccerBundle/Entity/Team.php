<?php

namespace Application\SoccerBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Team
 *
 * @ORM\Table(name="teams")
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks()
 */
class Team
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
    protected $teamId;

    /**
     *
     * @var string
     * 
     * @ORM\Column(type="string", length=255)
     */
    protected $name;

    /**
     *
     * @var Country
     * 
     * @ORM\ManyToOne(targetEntity="Country", inversedBy="teams", cascade={"persist"})
     */
    protected $country;

    /**
     *
     * @var SoccerMatch
     * 
     * @ORM\OneToMany(targetEntity="SoccerMatch", mappedBy="homeTeam", orphanRemoval=true, cascade={"persist"})
     */
    protected $homeTeams;
    
    /**
     *
     * @var SoccerMatch
     * 
     * @ORM\OneToMany(targetEntity="SoccerMatch", mappedBy="homeTeam", orphanRemoval=true, cascade={"persist"})
     */
    protected $awayTeams;
    
    /**
     *
     * @var string
     * 
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    protected $stadiumName;

    /**
     *
     * @var string
     * 
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    protected $homePageUrl;

    /**
     *
     * @var string
     * 
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    protected $wikiPageUrl;

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
        $this->homeTeams = new \Doctrine\Common\Collections\ArrayCollection();
        $this->awayTeams = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set teamId
     *
     * @param integer $teamId
     * @return Team
     */
    public function setTeamId($teamId)
    {
        $this->teamId = $teamId;

        return $this;
    }

    /**
     * Get teamId
     *
     * @return integer 
     */
    public function getTeamId()
    {
        return $this->teamId;
    }

    /**
     * Set name
     *
     * @param string $name
     * @return Team
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
     * Set stadiumName
     *
     * @param string $stadiumName
     * @return Team
     */
    public function setStadiumName($stadiumName)
    {
        $this->stadiumName = $stadiumName;

        return $this;
    }

    /**
     * Get stadiumName
     *
     * @return string 
     */
    public function getStadiumName()
    {
        return $this->stadiumName;
    }

    /**
     * Set homePageUrl
     *
     * @param string $homePageUrl
     * @return Team
     */
    public function setHomePageUrl($homePageUrl)
    {
        $this->homePageUrl = $homePageUrl;

        return $this;
    }

    /**
     * Get homePageUrl
     *
     * @return string 
     */
    public function getHomePageUrl()
    {
        return $this->homePageUrl;
    }

    /**
     * Set wikiPageUrl
     *
     * @param string $wikiPageUrl
     * @return Team
     */
    public function setWikiPageUrl($wikiPageUrl)
    {
        $this->wikiPageUrl = $wikiPageUrl;

        return $this;
    }

    /**
     * Get wikiPageUrl
     *
     * @return string 
     */
    public function getWikiPageUrl()
    {
        return $this->wikiPageUrl;
    }

    /**
     * Set createdTime
     *
     * @param \DateTime $createdTime
     * @return Team
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
     * @return Team
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
     * @return Team
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
     * Add homeTeams
     *
     * @param \Application\SoccerBundle\Entity\SoccerMatch $homeTeams
     * @return Team
     */
    public function addHomeTeam(\Application\SoccerBundle\Entity\SoccerMatch $homeTeams)
    {
        $this->homeTeams[] = $homeTeams;

        return $this;
    }

    /**
     * Remove homeTeams
     *
     * @param \Application\SoccerBundle\Entity\SoccerMatch $homeTeams
     */
    public function removeHomeTeam(\Application\SoccerBundle\Entity\SoccerMatch $homeTeams)
    {
        $this->homeTeams->removeElement($homeTeams);
    }

    /**
     * Get homeTeams
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getHomeTeams()
    {
        return $this->homeTeams;
    }

    /**
     * Add awayTeams
     *
     * @param \Application\SoccerBundle\Entity\SoccerMatch $awayTeams
     * @return Team
     */
    public function addAwayTeam(\Application\SoccerBundle\Entity\SoccerMatch $awayTeams)
    {
        $this->awayTeams[] = $awayTeams;

        return $this;
    }

    /**
     * Remove awayTeams
     *
     * @param \Application\SoccerBundle\Entity\SoccerMatch $awayTeams
     */
    public function removeAwayTeam(\Application\SoccerBundle\Entity\SoccerMatch $awayTeams)
    {
        $this->awayTeams->removeElement($awayTeams);
    }

    /**
     * Get awayTeams
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getAwayTeams()
    {
        return $this->awayTeams;
    }
}
