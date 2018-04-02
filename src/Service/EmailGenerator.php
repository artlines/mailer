<?php

namespace App\Service;

use App\Entity\Template;
use Doctrine\ORM\EntityManager;

class EmailGenerator
{
    /**
     * @var EntityManager $entityManager
     */
    private $entityManager;

    /**
     * EmailGenerator constructor.
     * @param EntityManager $entityManager
     */
    public function __construct(EntityManager $entityManager, \Twig_Environment $twig)
    {
        $this->entityManager = $entityManager;
        $this->template = $twig;
    }

    /**
     * @param Template $template
     * @param array $data
     * @return string
     */
    public function generate($template, array $data)
    {
        $template_text = $template->getTemplateText();

        $return = "";
        try {
            $return = $this->template->createTemplate($template_text)->render(['data' => $data]);
        } catch (\Twig_Error_Loader $e) {
            $return = $e->getMessage();
        } catch (\Twig_Error_Syntax $e) {
            $return = $e->getMessage();
        } catch (\Throwable $e) {
            $return = $e->getMessage();
        }

        return $return;
    }

}