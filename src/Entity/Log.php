<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Class Log
 * @package App\Entity
 *
 * @ORM\Entity(repositoryClass="App\Repository\LogRepository")
 * @ORM\Table(name="log")
 * @ORM\HasLifecycleCallbacks()
 */
class Log
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
     * @ORM\Column(name="mail_subject", type="string", length=255, nullable=false)
     */
    private $subject;

    /**
     * @var string
     *
     * @ORM\Column(name="mail_body", type="text", nullable=false)
     */
    private $body;

    /**
     * @var string
     *
     * @ORM\Column(name="ip_address", length=20, nullable=false)
     */
    private $ip_address;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="send_datetime", type="datetime", nullable=false)
     */
    private $time;

    /**
     * @var boolean
     *
     * @ORM\Column(name="send_status", type="boolean")
     */
    private $isSend;

    /**
     * @var string
     *
     * @ORM\Column(name="email_to", length=50, type="string", nullable=false)
     */
    private $emailTo;

    /**
     * @var string
     *
     * @ORM\Column(name="email_from", type="string", nullable=false)
     */
    private $emailFrom;

    /**
     * @var string
     *
     * @ORM\Column(name="email_cc", type="string", nullable=true)
     */
    private $emailCc;

    /**
     * @var string
     *
     * @ORM\Column(name="email_bcc", type="string", nullable=true)
     */
    private $emailBcc;

    /**
     * Log constructor.
     */
    public function __construct()
    {
    }

    /**
     * @ORM\PrePersist
     */
    public function setTime()
    {
        $this->time = new \DateTime();
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
    public function getMailSubject()
    {
        return $this->subject;
    }

    /**
     * @param string $subject
     */
    public function setMailSubject(string $subject): void
    {
        $this->subject = $subject;
    }

    /**
     * @return string
     */
    public function getMailBody()
    {
        return $this->body;
    }

    /**
     * @param string $body
     */
    public function setMailBody(string $body): void
    {
        $this->body = $body;
    }

    /**
     * @return string
     */
    public function getIpAddress()
    {
        return $this->ip_address;
    }

    /**
     * @param string $ip_address
     */
    public function setIpAddress(string $ip_address): void
    {
        $this->ip_address = $ip_address;
    }

    /**
     * @return string
     */
    public function getEmailFrom(): string
    {
        return $this->emailFrom;
    }

    /**
     * @param string $emailFrom
     */
    public function setEmailFrom(string $emailFrom): void
    {
        $this->emailFrom = $emailFrom;
    }

    /**
     * @return array
     */
    public function getEmailTo()
    {
        return json_decode($this->emailTo, true);
    }

    /**
     * @param array $emailTo
     */
    public function setEmailTo(array $emailTo): void
    {
        $this->emailTo = json_encode($emailTo);
    }

    /**
     * @return array
     */
    public function getEmailBcc(): array
    {
        return json_decode($this->emailBcc, true);
    }

    /**
     * @param array $emailBcc
     */
    public function setEmailBcc(array $emailBcc): void
    {
        $this->emailBcc = json_encode($emailBcc);
    }

    /**
     * @return array
     */
    public function getEmailCc(): array
    {
        return json_decode($this->emailCc, true);
    }

    /**
     * @param array $emailCc
     */
    public function setEmailCc(array $emailCc): void
    {
        $this->emailCc = json_encode($emailCc);
    }

    /**
     * @return bool
     */
    public function isSend()
    {
        return $this->isSend;
    }

    /**
     * @param bool $isSend
     */
    public function setIsSend(bool $isSend): void
    {
        $this->isSend = $isSend;
    }

}
