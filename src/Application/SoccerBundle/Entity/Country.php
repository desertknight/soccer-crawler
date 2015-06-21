<?php

namespace Application\SoccerBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Country
 *
 * @ORM\Table(name="countries")
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks()
 */
class Country
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
     * @var string
     * 
     * @ORM\Column(type="string", length=255)
     */
    protected $name;

    /**
     *
     * @var \Doctrine\Common\Collections\ArrayCollection
     * 
     * @ORM\OneToMany(targetEntity="League", mappedBy="country", orphanRemoval=true, cascade={"persist"})
     */
    protected $leagues;

    /**
     *
     * @var \Doctrine\Common\Collections\ArrayCollection
     * 
     * @ORM\OneToMany(targetEntity="Team", mappedBy="country", orphanRemoval=true, cascade={"persist"})
     */
    protected $teams;
    
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
        $this->leagues = new \Doctrine\Common\Collections\ArrayCollection();
        $this->teams = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set name
     *
     * @param string $name
     * @return Country
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
     * Set updatedTime
     *
     * @param \DateTime $updatedTime
     * @return Country
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
     * Set createdTime
     *
     * @param \DateTime $createdTime
     * @return Country
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
     * Add leagues
     *
     * @param \Application\SoccerBundle\Entity\League $leagues
     * @return Country
     */
    public function addLeague(\Application\SoccerBundle\Entity\League $leagues)
    {
        $this->leagues[] = $leagues;

        return $this;
    }

    /**
     * Remove leagues
     *
     * @param \Application\SoccerBundle\Entity\League $leagues
     */
    public function removeLeague(\Application\SoccerBundle\Entity\League $leagues)
    {
        $this->leagues->removeElement($leagues);
    }

    /**
     * Get leagues
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getLeagues()
    {
        return $this->leagues;
    }

    /**
     * Add teams
     *
     * @param \Application\SoccerBundle\Entity\Team $teams
     * @return Country
     */
    public function addTeam(\Application\SoccerBundle\Entity\Team $teams)
    {
        $this->teams[] = $teams;

        return $this;
    }

    /**
     * Remove teams
     *
     * @param \Application\SoccerBundle\Entity\Team $teams
     */
    public function removeTeam(\Application\SoccerBundle\Entity\Team $teams)
    {
        $this->teams->removeElement($teams);
    }

    /**
     * Get teams
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getTeams()
    {
        return $this->teams;
    }
}
