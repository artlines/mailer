<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Class Template
 * @package App\Entity
 *
 * @ORM\Table(name="template")
 * @ORM\Entity(repositoryClass="App\Repository\TemplateRepository")
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



}