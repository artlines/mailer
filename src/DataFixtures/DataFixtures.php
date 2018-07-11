<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use App\Entity\User;
use App\Entity\SendList;
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

            $user = new User();
            $user->setFullname('Пользователь ' . $i);
            $user->setEmail($i . 'test@test.ru');

            $password = $this->encoder->encodePassword($user, 'ZafsdfsdfsdfZ');
            $user->setPassword($password);

            $manager->persist($user);
            $manager->flush();

            $sendList = new SendList();
            $sendList->setName('Список ' . $i);
            $sendList->setEmails(
                'test@test.ru
                test@test.ru
                test@test.ru
                test@test.ru
                test@test.ru
                test@test.ru
                test@test.ru
                test@test.ru
                test@test.ru
                test@test.ru
            ');
            $sendList->setUserId($user);
            $sendList->setCreatedAt($this->dateTime->setDate('2018', 02, $i));

            $manager->persist($sendList);
            $manager->flush();
        }
    }
}
