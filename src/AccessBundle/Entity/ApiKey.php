<?php

namespace AccessBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

use JMS\Serializer\Annotation\ExclusionPolicy;
use JMS\Serializer\Annotation\Expose;
use JMS\Serializer\Annotation\Groups;
use JMS\Serializer\Annotation\VirtualProperty;

/**
 * ApiKey
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="AccessBundle\Entity\ApiKeyRepository")
 * @ORM\HasLifecycleCallbacks()
 * @ExclusionPolicy("all")
 */
class ApiKey
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;
    
    /**
     *
     * @ORM\ManyToOne(targetEntity="\ApiBundle\Entity\User", inversedBy="apiKeys")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id", onDelete="CASCADE")
     */
    private $user;

    /**
     * @var string
     *
     * @ORM\Column(name="apiKey", type="string", length=100)
     * @Expose
     */
    private $apiKey;

    /**
     * @var boolean
     *
     * @ORM\Column(name="isActive", type="boolean")
     */
    private $isActive;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="expiresAt", type="datetime")
     */
    private $expiresAt;


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
     * Set apiKey
     *
     * @param string $apiKey
     * @return ApiKey
     */
    public function setApiKey($apiKey)
    {
        $this->apiKey = $apiKey;

        return $this;
    }

    /**
     * Get apiKey
     *
     * @return string 
     */
    public function getApiKey()
    {
        return $this->apiKey;
    }

    /**
     * Set isActive
     *
     * @param boolean $isActive
     * @return ApiKey
     */
    public function setIsActive($isActive)
    {
        $this->isActive = $isActive;

        return $this;
    }

    /**
     * Get isActive
     *
     * @return boolean 
     */
    public function getIsActive()
    {
        return $this->isActive;
    }

    /**
     * Set expiresAt
     *
     * @param \DateTime $expiresAt
     * @return ApiKey
     */
    public function setExpiresAt($expiresAt)
    {
        $this->expiresAt = $expiresAt;

        return $this;
    }

    /**
     * Get expiresAt
     *
     * @return \DateTime 
     */
    public function getExpiresAt()
    {
        return $this->expiresAt;
    }
    
    /**
     * @ORM\PrePersist
     */
    public function setExpiresAtValue() {
    	$dateTime = new \DateTime();
    	$dateTime->modify('+1 month');
    	$this->expiresAt = $dateTime;
    }
     /**
     * @ORM\PrePersist
     */
    public function setIsActiveValue() {
        $this->isActive = false;
    }

    /**
     * Set user
     *
     * @param \AccessBundle\Entity\User $user
     * @return ApiKey
     */
    public function setUser(\AccessBundle\Entity\User $user = null)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return \AccessBundle\Entity\User 
     */
    public function getUser()
    {
        return $this->user;
    }
}
