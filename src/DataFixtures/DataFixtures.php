<?php

namespace App\DataFixtures;

use App\Entity\SendList;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use App\Entity\User;
use App\Entity\Template;
use DateTimeImmutable;


class DataFixtures extends Fixture
{
    private $encoder;
    private $dateTime;

    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
        $this->dateTime = new DateTimeImmutable();
    }

    public function load(ObjectManager $manager)
    {
        for ($i = 0; $i++ < 30;) {
            //пользователи
            $user = new User();
            $user->setFullname('Пользователь ' . $i);
            $user->setEmail($i . 'test@test.ru');

            $password = $this->encoder->encodePassword($user, 'ZafsdfsdfsdfZ');
            $user->setPassword($password);

            $manager->persist($user);
            $manager->flush();

            //списки рассылки
            $sendList = new SendList();
            $this->dateTime = new DateTimeImmutable();
            $sendList->setCreatedAt($this->dateTime);
            $sendList->setName('Список ' . $i);
            $sendList->setEmails('test@test.ru
            test@test.ru
            test@test.ru
            test@test.ru
            test@test.ru
            test@test.ru
            test@test.ru
            test@test.ru
            test@test.ru');
            $sendList->setUserId($user);

            $manager->persist($sendList);
            $manager->flush();

            //шаблоны
            $template = new Template();
            $template->setTitle('Шаблон ' . $i);
            $template->setAlias('template_' . $i);
            $template->setIsActive(true);
            $template->setIsPrivate(true);
            $text = file_get_contents(__DIR__.'/../../templates/base.html.twig');
            $template->setText($text);
            $manager->persist($template);
            $manager->flush();
        }
    }
}
