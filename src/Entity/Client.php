<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use App\Entity\User;

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
     * @ORM\Column(name="sender_email", nullable=false)
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
     * @ORM\ManyToMany(targetEntity="Template", mappedBy="clients")
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
     * @return array|null
     */
    public function getAllowIPs(): ?array
    {
        return is_null($this->allowIPs) ? null : json_decode($this->allowIPs, true);
    }

    /**
     * @param array|null $allowIPs
     */
    public function setAllowIPs(?array $allowIPs): void
    {
        $this->allowIPs = is_null($allowIPs) ? null : json_encode($allowIPs);
    }

    /**
     * @return string
     */
    public function getClientSecret()
    {
        return $this->clientSecret;
    }

    /**
     * @param string $secret
     */
    public function setClientSecret($secret)
    {
        $this->clientSecret = $secret ?? md5(random_bytes(18));
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

    /**
     * Метод добавляет шаблон письма.
     * 
     * @param Template $template Шаблон письма
     *
     * @return Client
     */
    public function addTemplate(Template $template): self
    {
        if (!$this->templates->contains($template)) {
            $this->templates[] = $template;
            $template->addClient($this);
        }

        return $this;
    }
    
    /**
     * Метод удаляет шаблон письма.
     * 
     * @param Template $template Шаблон письма
     * 
     * @return Client
     */
    public function removeTemplate(Template $template): self
    {
        if ($this->templates->contains($template)) {
            $this->templates->removeElement($template);
            $template->removeClient($this);
        }

        return $this;
    }

}
