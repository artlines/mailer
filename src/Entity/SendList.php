<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="`send_list`")
 * @ORM\Entity(repositoryClass="App\Repository\SendListRepository")
 */
class SendList
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
    private $created_at;

    /**
     * @var int Идентификатор пользователя
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="sendList")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id", nullable=false)
     */
    private $userId;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $emails;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Dispatch", mappedBy="send_list")
     */
    private $dispatches;

    /**
     * SendList constructor.
     */
    public function __construct()
    {
        $this->dispatches = new ArrayCollection();
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

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->created_at;
    }

    public function setCreatedAt(\DateTimeInterface $created_at): self
    {
        $this->created_at = $created_at;

        return $this;
    }

    public function getUserId(): ?User
    {
        return $this->userId;
    }

    public function setUserId(?User $userId): self
    {
        $this->userId = $userId;

        return $this;
    }

    public function getEmails(): ?string
    {
        return $this->emails;
    }

    public function setEmails(?string $emails): self
    {
        $this->emails = $emails;

        return $this;
    }

    public function getDispatches(): ?Dispatch
    {
        return $this->dispatches;
    }

    public function setDispatches(?Dispatch $dispatches): self
    {
        $this->dispatches = $dispatches;

        return $this;
    }
}
