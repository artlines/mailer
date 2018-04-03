<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Class Client
 * @package App\Entity
 *
 * @ORM\Entity(repositoryClass="App\Repository\ClientRepository")
 * @ORM\Table(name="client")
 * @ORM\HasLifecycleCallbacks()
 */
class Client
{
    /**
     * @var int
     *
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="title", length=50, type="string", unique=true)
     */
    private $title;

    /**
     * @var string
     *
     * @ORM\Column(name="alias", length=50, nullable=false, unique=true)
     */
    private $alias;

    /**
     * @var string
     *
     * @ORM\Column(name="sender", nullable=true)
     */
    private $sender;

    /**
     * @var array|null
     *
     * @ORM\Column(name="allow_ips", type="string", nullable=true)
     */
    private $allowIPs;

    /**
     * @var string
     *
     * @ORM\Column(name="client_secret", type="string", nullable=false)
     */
    private $clientSecret;

    /**
     * @var bool
     *
     * @ORM\Column(name="is_active", type="boolean", nullable=false)
     */
    private $isActive;

    /**
     * @var ArrayCollection
     *
     * @ORM\ManyToMany(targetEntity="Template", inversedBy="clients")
     * @ORM\JoinTable(name="templates_clients")
     */
    private $templates;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created_at", type="datetime")
     */
    private $createdAt;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="updated_at", type="datetime", nullable=true)
     */
    private $updatedAt;


    /**
     * Client constructor.
     */
    public function __construct()
    {
        $this->isActive = true;
        $this->templates = new ArrayCollection();
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * @param string $title
     */
    public function setTitle(string $title): void
    {
        $this->title = $title;
    }

    /**
     * @return string
     */
    public function getAlias(): string
    {
        return $this->alias;
    }

    /**
     * @param string $alias
     */
    public function setAlias(string $alias): void
    {
        $this->alias = $alias;
    }

    /**
     * @return array|int
     */
    public function getAllowIPs()
    {
        if (null === $this->allowIPs)
            return 1;

        return json_decode($this->allowIPs, true);
    }

    /**
     * @param array $allowIPs
     */
    public function setAllowIPs(?array $allowIPs): void
    {
        $this->allowIPs = json_encode($allowIPs);
    }

    /**
     * @return string
     */
    public function getClientSecret()
    {
        return $this->clientSecret;
    }

    /**
     * @ORM\PrePersist()
     */
    public function setClientSecret()
    {
        $this->clientSecret = md5(uniqid());
    }

    /**
     * @return bool
     */
    public function isActive()
    {
        return $this->isActive;
    }

    /**
     * @param bool $isActive
     */
    public function setIsActive(bool $isActive): void
    {
        $this->isActive = $isActive;
    }

    /**
     * @ORM\PrePersist
     */
    public function setCreatedAt()
    {
        $this->createdAt = new \DateTime();
    }

    /**
     * @return \DateTime
     */
    public function getCreatedAt(): \DateTime
    {
        return $this->createdAt;
    }

    /**
     * @ORM\PreUpdate
     */
    public function setUpdatedAt()
    {
        $this->updatedAt = new \DateTime();
    }

    /**
     * @return \DateTime|null
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    /**
     * @return ArrayCollection
     */
    public function getTemplates()
    {
        return $this->templates;
    }

    /**
     * @param Template $template
     */
    public function addTemplate($template)
    {
        if (!$this->templates->contains($template))
            $this->templates->add($template);
    }

    /**
     * @param Template $template
     */
    public function removeTemplate($template)
    {
        if ($this->templates->contains($template))
            $this->templates->removeElement($template);
    }

    /**
     * @return string
     */
    public function getSender()
    {
        return $this->sender;
    }

    /**
     * @param string $sender
     */
    public function setSender(string $sender): void
    {
        $this->sender = $sender;
    }

    

}