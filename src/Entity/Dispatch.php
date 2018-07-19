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
    public function getSendList(): Collection
    {
        return $this->send_list;
    }

    public function addSendList(SendList $sendList): self
    {
        if (!$this->send_list->contains($sendList)) {
            $this->send_list[] = $sendList;
            $sendList->setDispatches($this);
        }

        return $this;
    }

    public function removeSendList(SendList $sendList): self
    {
        if ($this->send_list->contains($sendList)) {
            $this->send_list->removeElement($sendList);
            // set the owning side to null (unless already changed)
            if ($sendList->getDispatches() === $this) {
                $sendList->setDispatches(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Template[]
     */
    public function getTemplate(): Collection
    {
        return $this->template;
    }

    public function addTemplate(Template $template): self
    {
        if (!$this->template->contains($template)) {
            $this->template[] = $template;
            $template->setDispatches($this);
        }

        return $this;
    }

    public function removeTemplate(Template $template): self
    {
        if ($this->template->contains($template)) {
            $this->template->removeElement($template);
            // set the owning side to null (unless already changed)
            if ($template->getDispatches() === $this) {
                $template->setDispatches(null);
            }
        }

        return $this;
    }
}
