<?php

namespace Application\SoccerBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * System
 *
 * @ORM\Table(name="systems")
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks()
 */
class System
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
    protected $command;
    
    /**
     *
     * @var string
     * 
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    protected $response;
    
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
     * Set command
     *
     * @param string $command
     * @return System
     */
    public function setCommand($command)
    {
        $this->command = $command;

        return $this;
    }

    /**
     * Get command
     *
     * @return string 
     */
    public function getCommand()
    {
        return $this->command;
    }

    /**
     * Set response
     *
     * @param string $response
     * @return System
     */
    public function setResponse($response)
    {
        $this->response = $response;

        return $this;
    }

    /**
     * Get response
     *
     * @return string 
     */
    public function getResponse()
    {
        return $this->response;
    }

    /**
     * Set createdTime
     *
     * @param \DateTime $createdTime
     * @return System
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
     * @return System
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
}
