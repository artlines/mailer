<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use App\Entity\User;

/**
 * Класс сущности "Журнал действий пользователя"
 *
 * @category   Symfony
 * @package    App\Entity
 * @author     Седов Стас, <s.sedov@nag.ru>
 * @copyright  Copyright (c) 20018 NAG LLC. (https://www.shop.nag.ru)
 * @version    0.0.4
 * 
 * @ORM\Entity(repositoryClass="App\Repository\ActionLogRepository")
 * @ORM\Table(name="action_log")
 */
class ActionLog
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @var \DateTimeInterface Время внесения записи
     * 
     * @ORM\Column(type="datetime")
     */
    private $datetime;

    /**
     * @var int Идентификатор пользователя
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="actionLog")
     * @ORM\JoinColumn(nullable=false, name="user_id", referencedColumnName="id")
     */
    private $userId;

    /**
     * @var string Список действий пользователя
     * 
     * @ORM\Column(type="string", length=50)
     */
    private $action;

    /**
     * @var string Название сущности
     * 
     * @ORM\Column(type="string", length=255)
     */
    private $entity;

    /**
     * @var int Идентификатор сущности
     * 
     * @ORM\Column(name="entity_id", type="integer")
     */
    private $entityId;

    /**
     * @var string Сообщение
     * 
     * @ORM\Column(type="string", length=255)
     */
    private $message;

    public function __construct()
    {
        $this->users = new ArrayCollection();
    }
    
    /**
     * Метод возвращает идентификатор записи.
     *     
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }
    
    /**
     * Метод возвращает время внесение записи в журнал.
     * 
     * @return \DateTimeInterface|null
     */
    public function getDatetime(): ?\DateTime
    {
        return $this->datetime;
    }
    
    /**
     * Метод устанавливает время внсения записи в журнал.
     * 
     * @param \DateTimeInterface $datetime Время
     *
     * @return ActionLog
     */
    public function setDatetime(): self
    {
        $this->datetime = new \DateTime();

        return $this;
    }

    /**
     * Метод получает идентификатор пользователя.
     * 
     * @return User|null
     */
    public function getUserId(): ?User
    {
        return $this->userId;
    }

    /**
     * Метод устанавливает идентификатор пользователя.
     * 
     * @param User $userId Идентификатор пользователя
     *
     * @return ActionLog
     */
    public function setUserId(?User $userId): self
    {
        $this->userId = $userId;

        return $this;
    }
    
    /**
     * Метод возвращает действие пользователя.
     * 
     * @return string|null
     */
    public function getAction(): ?string
    {
        return $this->action;
    }

    /**
     * Метод устанавливает действие пользователя.
     * 
     * @param string $action Действие пользователя
     *
     * @return ActionLog
     */
    public function setAction(string $action): self
    {
        $this->action = $action;

        return $this;
    }
    
    /**
     * Метод получает название сущности.
     * 
     * @return string|null
     */
    public function getEntity(): ?string
    {
        return $this->entity;
    }
    
    /**
     * Метод устанавливает название сущности.
     * 
     * @param string $entity Название сущности
     *
     * @return ActionLog
     */
    public function setEntity(string $entity): self
    {
        $this->entity = $entity;

        return $this;
    }

    /**
     * Метод возвращает идентификатор сущности.
     * 
     * @return int|null
     */
    public function getEntityId(): ?int
    {
        return $this->entityId;
    }
    
    /**
     * Метод устанавливает идентификатор сущности.
     * 
     * @param int $entityId Идентификатор сущности
     *
     * @return ActionLog
     */
    public function setEntityId(int $entityId): self
    {
        $this->entityId = $entityId;

        return $this;
    }
    
    /**
     * Метод возвращает сообщение.
     * 
     * @return string|null
     */
    public function getMessage(): ?string
    {
        return $this->message;
    }
    
    /**
     * Метод устанавливает сообщение.
     * 
     * @param string $message Текст сообщения
     *
     * @return string|null
     */
    public function setMessage(string $message): self
    {
        $this->message = $message;

        return $this;
    }
}
