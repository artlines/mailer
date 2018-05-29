<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Class Template
 * @package App\Entity
 *
 * @ORM\Table(name="template")
 * @ORM\Entity(repositoryClass="App\Repository\TemplateRepository")
 *
 * @ORM\HasLifecycleCallbacks()
 */
class Template
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
     * @ORM\Column(name="title", type="string", nullable=false, unique=true)
     */
    private $title;

    /**
     * @var string
     *
     * @ORM\Column(name="alias", type="string", nullable=false, unique=true)
     */
    private $alias;

    /**
     * @var bool
     *
     * @ORM\Column(name="is_active", type="boolean", nullable=false)
     */
    private $isActive;

    /**
     * @var boolean
     *
     * @ORM\Column(name="is_private", type="boolean", nullable=false, options={"default":1})
     */
    private $isPrivate;

    /**
     * @var string
     *
     * @ORM\Column(name="template_text", type="text")
     */
    private $templateText;

    /**
     * @var ArrayCollection
     *
     * @ORM\ManyToMany(targetEntity="Client", inversedBy="templates")
     * @ORM\JoinTable(name="templates_clients")
     */
    private $clients;

    /**
     * Template constructor.
     */
    public function __construct()
    {
        $this->isPrivate = true;
        $this->clients = new ArrayCollection();
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getTitle()
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
    public function getAlias()
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
     * @return string
     */
    public function getTemplateText()
    {
        return $this->templateText;
    }

    /**
     * @param string $templateText
     */
    public function setTemplateText(string $templateText): void
    {
        $this->templateText = $templateText;
    }

    /**
     * @return ArrayCollection
     */
    public function getClients()
    {
        return $this->clients;
    }

    /**
     * @param Client $client
     */
    public function addClient($client)
    {
        if (!$this->clients->contains($client))
            $this->clients->add($client);
    }

    /**
     * @param Client $client
     */
    public function removeClient($client)
    {
        if ($this->clients->contains($client))
            $this->clients->removeElement($client);
    }

    /**
     * @return bool
     */
    public function isPrivate()
    {
        return $this->isPrivate;
    }

    /**
     * @param $isPrivate boolean
     */
    public function setPrivate($isPrivate)
    {
        $this->isPrivate = $isPrivate;
    }

    /**
     * Check permissions to usage template
     *
     * @param Client $client
     * @return bool
     */
    public function canUseByClient(Client $client)
    {
        if (!$this->isPrivate())
            return true;

        if ($this->clients->contains($client))
            return true;

        return false;
    }

}