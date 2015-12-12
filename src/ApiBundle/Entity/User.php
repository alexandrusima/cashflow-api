<?php

namespace ApiBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\Encoder\EncoderAwareInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;
use JMS\Serializer\Annotation as JMS;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;


/**
 * User
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="ApiBundle\Entity\UserRepository")
 * @ORM\HasLifecycleCallbacks()
 * @JMS\ExclusionPolicy("all")
 * @UniqueEntity(
 *  fields={"username"},
 *  message="Username is already used.",
 *  repositoryMethod="getByUsername",
 *  groups={"auth_register"}
 * )
 */
class User implements UserInterface, EncoderAwareInterface
{
     /**
     * @ORM\OneToMany(targetEntity="AccessBundle\Entity\ApiKey", mappedBy="user", cascade={"remove", "persist"})
     * @JMS\Expose
     * @JMS\Groups({"me"})
     */
    protected $apikeys;

    public function __construct() {
        $this->apikeys = new ArrayCollection();
    }

   	/**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @JMS\Expose
     * @JMS\Groups({"me", "list"})
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="firstName", type="string", length=100)
     * @JMS\Expose
     * @JMS\Groups({"me", "list"})
     * @Assert\NotBlank(groups={"auth_register"})
     */
    private $firstName;

    /**
     * @var string
     *
     * @ORM\Column(name="lastName", type="string", length=100)
     * @JMS\Expose
     * @JMS\Groups({"me", "list"})
     * @JMS\Accessor(getter="getLastName",setter="setLastName")
     * @Assert\NotBlank(groups={"auth_register"})
     */
    private $lastName;

    /**
     * @var string
     *
     * @ORM\Column(name="username", type="string", length=50)
     * @JMS\Expose
     * @JMS\Groups({"me", "list"})
     * @Assert\Email(groups={"auth_getApiKey", "auth_register"})
     */
    private $username;

    /**
     * @var \DateTime
     * @ORM\Column(name="lastLogin", type="datetimetz", nullable=true)
     * @JMS\Expose
     * @JMS\Groups({"me"})
     */
    private $lastLogin;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created_at", type="datetime")
     * @JMS\Expose
     * @JMS\Groups({"me"})
     */
    private $createdAt;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="updated_at", type="datetime", nullable=true)
     */
    private $updatedAt;

    /**
     * @var string
     *
     * @ORM\Column(name="password", type="string", length=255, nullable=false)
     * @Assert\NotBlank()
     * @Assert\Regex(
     *  pattern="/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[$@$!%*?&])[A-Za-z\d$@$!%*?&]{4,20}$/",
     *  match=true,
     *  message="Minimum 4 and Maximum 20 characters at least 1 Uppercase Alphabet, 1 Lowercase Alphabet, 1 Number and 1 Special Character",
     * groups={"auth_register"}
     * )
     */
    private $password;

	/**
     * @var string
     *
     * @ORM\Column(name="salt", type="string", length=255, nullable=true)
     */
    private $salt;

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
     * Set firstName
     *
     * @param string $firstName
     * @return User
     */
    public function setFirstName($firstName)
    {
        $this->firstName = $firstName;

        return $this;
    }

    /**
     * Get firstName
     *
     * @return string
     */
    public function getFirstName()
    {
        return $this->firstName;
    }

    /**
     * Set lastName
     *
     * @param string $lastName
     * @return User
     */
    public function setLastName($lastName)
    {
        $this->lastName = $lastName;

        return $this;
    }

    /**
     * Get lastName
     *
     * @return string
     */
    public function getLastName()
    {
        return mb_strtoupper($this->lastName);
    }

    /**
     * Set username
     *
     * @param string $username
     * @return User
     */
    public function setUsername($username)
    {
        $this->username = $username;

        return $this;
    }

    /**
     * Get username
     *
     * @return string
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * Set lastLogin
     *
     * @param \DateTime $lastLogin
     * @return User
     */
    public function setLastLogin($lastLogin)
    {
        $this->lastLogin = $lastLogin;

        return $this;
    }

    /**
     * Get lastLogin
     *
     * @return \DateTime
     */
    public function getLastLogin()
    {
        return $this->lastLogin;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     * @return User
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * Get createdAt
     *
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * Set updatedAt
     *
     * @param \DateTime $updatedAt
     * @return User
     */
    public function setUpdatedAt($updatedAt)
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    /**
     * Get updatedAt
     *
     * @return \DateTime
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    /**
     * Set password
     *
     * @param string $password
     * @return User
     */
    public function setPassword($password)
    {
    	$this->password = $password;
        return $this;
    }

    /**
     * Get password
     *
     * @return string
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * this method returns null.
     * this signals that symfony should use the default
     * encoder
     * @return null
     */
    public function getEncoderName()
    {
        return null;
    }
    
    /**
     * Get the formatted name to display (NAME Firstname or username)
     *
     * @param $separator: the separator between name and firstname (default: ' ')
     * @return String
     * @JMS\VirtualProperty
     * @@JMS\Groups({"me", "list"})
     */
    public function getFullName($separator = ' '){
        if($this->getLastName()!=null && $this->getFirstName()!=null){
            return ucfirst(strtolower($this->getFirstName())).$separator.strtoupper($this->getLastName());
        }
        else{
            return $this->getUsername();
        }
    }

    /**
     * Method inherited from UserInterface
     * @return [type] [description]
     * @@JMS\VirtualProperty
     * @@JMS\Groups({"me"})
     */
    public function getRoles() {
        return array('ROLE_USER');
    }

    public function setSalt($salt) {
    	$this->salt = $salt;
    	return $this;
    }
    /**
     * Method inherited from UserInterface
     * @return [type] [description]
     */
    public function getSalt() {
		return $this->salt;
    }

    /**
     * Method inherited from UserInterface
     * @return [type] [description]
     */
    public function eraseCredentials()
    {
        $this->setPassword(null);
    }
    
    /**
     * This method is called before
     * saving entity in the database
     * @ORM\PrePersist
     */
    public function setCreatedAtValue()
    {
    	if(empty($this->createdAt)) {
    		$this->createdAt = new \DateTime();
    	}
    	return $this;
    }

    /**
     * This method is called before
     * saving entity in the database
     * @ORM\PreUpdate
     */
    public function setUpdatedAtValue()
    {
    	if(empty($this->updatedAt)) {
    		$this->updatedAt = new \DateTime();
    	}
    	return $this;
    }

    /**
     *
     * @param \AccessBundle\Entity\ApiKey $apikey
     * @return User
     */
    public function addApiKey(\AccessBundle\Entity\ApiKey $apiKey)
    {
        $apiKey->setUser($this);
        $this->apikeys[] = $apiKey;
        return $this;
    }

    /**
     * Remove apiKey
     *
     * @param \AccessBundle\Entity\ApiKey $apiKey
     */
    public function removeApiKey(\AccessBundle\Entity\ApiKey $apiKey)
    {
        $this->apikeys->removeElement($apiKey);
    }

    /**
     * Get apikeys
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getApiKeys()
    {
        return $this->apikeys;
    }

    /**
     * this method will generate a new password
     * salt
     */
    public function generateSalt() {
        $newSalt = md5(uniqid());
        return $newSalt;
    }

    public function getApiKeyByType($type) {
        return array_filter($this->apikeys, function ($item, $key) use ($type) {
            return $type === $item->getType();
        });
    }
}
