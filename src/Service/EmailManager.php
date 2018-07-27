<?php

namespace App\Service;

use App\Entity\Template;
use Doctrine\ORM\EntityManager;

class EmailManager
{
    const CONTENT_HTML = 'text/html';

    /**
     * @var \Swift_Mailer
     */
    private $mailer;

    /**
     * @var \Twig_Environment
     */
    private $twig;

    /**
     * EmailManager constructor.
     *
     * @param \Twig_Environment $twig
     * @param \Swift_Mailer $mailer
     */
    public function __construct(\Twig_Environment $twig, \Swift_Mailer $mailer)
    {
        $this->twig = $twig;
        $this->mailer = $mailer;
    }

    /**
     * Generate email body from template and given data
     *
     * @param Template $template
     * @param array $data
     * @param null $contentType
     * @param null $charset
     *
     * @return array
     *
     * @throws \Exception
     */
    public function generateBodyFromTemplate($template, array $data, $contentType = null, $charset = null)
    {
        $template_text = $template->getText();

        try {
            $body = $this->twig->createTemplate($template_text)->render(['data' => $data]);
        } catch (\Twig_Error_Loader $e) {
            throw new \Exception($e->getMessage());
        } catch (\Twig_Error_Syntax $e) {
            throw new \Exception($e->getMessage());
        } catch (\Throwable $e) {
            throw new \Exception($e->getMessage());
        }

        return [
            'body'          => $body,
            'contentType'   => $contentType,
            'charset'       => $charset
        ];
    }

    /**
     * Send email
     *
     * @param string $subject
     * @param array $bodyData
     * @param string $from
     * @param mixed $to
     * @param array|null $cc
     * @param array|null $bcc
     *
     * @return array
     */
    public function send(string $subject, array $bodyData, string $from, $to, $cc = [], $bcc = [], $dispatch, $dispatchManager)
    {
        /** @var \Swift_Message $sm */
        $sm = new \Swift_Message($subject);

        if (!is_array($to)){
            $to = [0 => $to];
        }

        foreach ($to as $email){
            $sm
                ->setFrom($from)
                ->setTo($to)
                ->setCc($cc)
                ->setBcc($bcc)
                ->setBody($bodyData['body'], $bodyData['contentType'], $bodyData['charset']);

            if ($this->mailer->send($sm)){
                $dispatchManager->cleanDispatchLog($dispatch, $email);
            }
        }

        return [
            'sm' => $sm,
            'status' => true,
        ];
    }

}