<?php

namespace AccessBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

use JMS\Serializer\Annotation\ExclusionPolicy;
use JMS\Serializer\Annotation\Expose;
use JMS\Serializer\Annotation\Groups;
use JMS\Serializer\Annotation\VirtualProperty;
use JMS\Serializer\Annotation\Accessor;
use JMS\Serializer\Annotation\AccessorOrder;

/**
 * ApiKey
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="AccessBundle\Entity\ApiKeyRepository")
 * @ORM\HasLifecycleCallbacks()
 * @ExclusionPolicy("all")
 * @AccessorOrder("alphabetical")
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
     * @ORM\ManyToOne(targetEntity="\ApiBundle\Entity\User", inversedBy="apikeys")
     */
    private $user;

    /**
     * @var string
     *
     * @ORM\Column(name="apiKey", type="string", length=100)
     * @Expose
     * @Accessor(getter="getApiKey",setter="setApiKey")
     * @Groups({"me", "auth_getApiKey"})
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
     * @Expose
     * @Groups({"me", "auth_getApiKey"})
     */
    private $expiresAt;
    
    /**
     * @var string
     *
     * @ORM\Column(name="type", type="string", columnDefinition="enum('desktop', 'mobile', 'tablet', 'password')"))
     */
    private $type;

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
        if($this->getType() != 'password') {
            $dateTime->modify('+1 month');
        }
        else {
            $dateTime->modify('+2 day');
        }
    	$this->expiresAt = $dateTime;
    }
     /**
     * @ORM\PrePersist
     */
    public function setIsActiveValue() {
        if(!isset($this->isActive)) {
            $this->isActive = false;
        }
    }

    /**
     * Set user
     *
     * @param \ApiBundle\Entity\User $user
     * @return ApiKey
     */
    public function setUser(\ApiBundle\Entity\User $user = null)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return \ApiBundle\Entity\User 
     */
    public function getUser()
    {
        return $this->user;
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
     * Set type
     *
     * @param string $type
     * @return ApiKey
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get type
     *
     * @return string 
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @ORM\PrePersist
     */
    public function setApiKeyValue()
    {
        $key = sha1( uniqid() . md5( rand() . uniqid() ) );
        $key = implode('-', str_split($key, 4));
        if(empty($this->apiKey)) {
            $this->setApiKey($key);
        }
        return $this;
    }
}
