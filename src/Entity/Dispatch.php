<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\DispatchRepository")
 */
class Dispatch
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=500)
     */
    private $name;

    /**
     * @ORM\Column(type="datetime")
     */
    private $datetime;

    /**
     * @ORM\Column(type="text")
     */
    private $subject;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\SendList", inversedBy="dispatches")
     * @ORM\JoinColumn(nullable=false, name="send_list_id", referencedColumnName="id")
     */
    private $send_list;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Template", inversedBy="dispatches")
     * @ORM\JoinColumn(nullable=false, name="template_id", referencedColumnName="id")
     */
    private $template;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name_from;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $email_from;

    public function __construct()
    {
        $this->send_list = new ArrayCollection();
        $this->template = new ArrayCollection();
    }

    public function getId()
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getDatetime(): ?\DateTimeInterface
    {
        return $this->datetime;
    }

    public function setDatetime(\DateTimeInterface $datetime): self
    {
        $this->datetime = $datetime;

        return $this;
    }

    public function getSubject(): ?string
    {
        return $this->subject;
    }

    public function setSubject(string $subject): self
    {
        $this->subject = $subject;

        return $this;
    }

    /**
     * @return Collection|SendList[]
     */
    public function getSendList()
    {
        return $this->send_list;
    }

    /**
     * @return Collection|Template[]
     */
    public function getTemplate()
    {
        return $this->template;
    }


    public function setSendList(?SendList $send_list): self
    {
        $this->send_list = $send_list;

        return $this;
    }

    public function setTemplate(?Template $template): self
    {
        $this->template = $template;

        return $this;
    }

    public function getNameFrom(): ?string
    {
        return $this->name_from;
    }

    public function setNameFrom(string $name_from): self
    {
        $this->name_from = $name_from;

        return $this;
    }

    public function getEmailFrom(): ?string
    {
        return $this->email_from;
    }

    public function setEmailFrom(string $email_from): self
    {
        $this->email_from = $email_from;

        return $this;
    }
}
