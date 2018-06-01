<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Класс сущности "Пользователь"
 *
 * @category   Symfony
 * @package    App\Entity
 * @author     Седов Стас, <s.sedov@nag.ru>
 * @copyright  Copyright (c) 20018 NAG LLC. (https://www.shop.nag.ru)
 * @version    0.0.4
 * 
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 */
class User
{
    /**
     * @var  int
     * 
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    private $id;
    
    /**
     * @var string Ф.И.О. пользователя
     *
     * @ORM\Column(name="fullname", type="string", length=255, nullable=true)
     */
    private $fullname;

    /**
     * @var string Эл. адрес пользователя
     * 
     * @ORM\Column(name="email", type="string", length=255, nullable=false, unique=true)
     */
    private $email;
    
    /**
     * @var string Пароль пользователя
     * 
     * @ORM\Column(name="password", type="string", length=255, nullable=false)
     */
    private $password;
    
    /**
     * @var bool Флаг активности пользователя
     *
     * @ORM\Column(name="is_active", type="boolean", nullable=false)
     */
    private $isActive;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Client", mappedBy="userId")
     */
    private $clients;

    public function __construct()
    {   
        $this->isActive = true;
        $this->clients = new ArrayCollection();
    }
    
    /**
     * Метод возвращает идентификатор пользователя.
     *     
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }
    
    /**
     * Метод возвращает эл. адрес пользователя (для регистрации)
     *     
     * @return string
     */
    public function getEmail(): ?string
    {
        return $this->email;
    }
    
    /**
     * Метод устанавливает эл. адрес пользователя. 
     * 
     * @param string $email Эл. адрес пользователя
     *
     * @return User
     */
    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }
    
    /**
     * Метод возвращает Ф.И.О. пользователя.
     * 
     * @return string|null
     */
    public function getFullname(): ?string
    {
        return $this->fullname;
    }
    
    /**
     * Метод устанавливает Ф.И.О. пользователя.
     * 
     * @param string $fullname Ф.И.О. пользователя.
     *
     * @return User
     */
    public function setFullname(?string $fullname): self
    {
        $this->fullname = $fullname;

        return $this;
    }
    
    /**
     * Метод возвращает пароль пользователя.
     * 
     * @return string|null
     */
    public function getPassword(): ?string
    {
        return $this->password;
    }
    
    /**
     * Метод устанавливает пароль пользователя.
     * 
     * @param string $password Пароль пользователя
     *
     * @return User
     */
    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }
    
    /**
     * Метод возвращает флаг активности пользователя.
     * 
     * @return bool
     */
    public function getIsActive(): ?bool
    {
        return $this->isActive;
    }
    
    /**
     * Метод устанавливает флаг активности пользователя.
     * 
     * @param bool $isActive Флаг активности пользователя
     *
     * @return User
     */
    public function setIsActive(bool $isActive): self
    {
        $this->isActive = $isActive;

        return $this;
    }
    
    /**
     * Метод возвращает флаг разрешения использования API.
     *
     * Если true - поле с ключом "client_secret" в таблице App\Entity\Client
     * обязательно для заполнения.
     * 
     * @return bool
     */
    public function getApi(): ?bool
    {
        return $this->api;
    }
    
    /**
     * Метод устанавливает флаг разрешения использования API.
     * 
     * @param bool $api Флаг разрешения использования API.
     *
     * @return User
     */
    public function setApi(bool $api): self
    {
        $this->api = $api;

        return $this;
    }

    /**
     * Метод возвращает клиента.
     * 
     * @return Collection|Client[]
     */
    public function getClients(): Collection
    {
        return $this->clients;
    }
    
    /**
     * Метод добавляет клиента.
     * 
     * @param Client $client Идентификатор клиента
     *
     * @return User
     */
    public function addClient(Client $client): self
    {
        if (!$this->clients->contains($client)) {
            $this->clients[] = $client;
            $client->setUserId($this);
        }

        return $this;
    }
    
    /**
     * Метод удаляет клиента.
     * @param  Client $client Идентификатор клиента
     * 
     * @return User
     */
    public function removeClient(Client $client): self
    {
        if ($this->clients->contains($client)) {
            $this->clients->removeElement($client);
            // set the owning side to null (unless already changed)
            if ($client->getUserId() === $this) {
                $client->setUserId(null);
            }
        }

        return $this;
    }

}
