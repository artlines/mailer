<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use App\Entity\User;

class UserFixtures extends Fixture
{
    private $encoder;

    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }

    public function load(ObjectManager $manager)
    {
        for ($i = 0; $i++ < 100;) {

            $user = new User();
            $user->setFullname('Пользователь ' . $i);
            $user->setEmail($i . 'test@test.ru');

            $password = $this->encoder->encodePassword($user, 'ZafsdfsdfsdfZ');
            $user->setPassword($password);

            $manager->persist($user);
            $manager->flush();
        }
    }
}
