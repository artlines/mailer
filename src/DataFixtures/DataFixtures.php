<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use App\Entity\User;
use App\Entity\DispatchStatus;
use DateTimeImmutable;
use App\Service\ClientManager;


class DataFixtures extends Fixture
{
    private $encoder;
    private $dateTime;
    private $clientManager;

    public function __construct(UserPasswordEncoderInterface $encoder, ClientManager $clientManager)
    {
        $this->encoder = $encoder;
        $this->clientManager = $clientManager;
        $this->dateTime = new DateTimeImmutable();
    }

    public function load(ObjectManager $manager)
    {
        //пользователь
        $user = new User();
        $user->setFullname('Ноль пользователь');
        $user->setEmail('user@null.ru');

        $password = $this->encoder->encodePassword($user, 'rZ2YXVfXMIXv');
        $user->setPassword($password);

        $manager->persist($user);
        $manager->flush();

        //клиент
        $this->clientManager->create('mailer', 'mailer', 'no-reply@nag.ru', null);

        //статусы рассылки
        $statuses = [
            'raw' => 'Черновик',
            'ready' => 'Готова к отправке',
            'process' => 'В процессе отправки',
            'complete' => 'Завершена'
        ];

        foreach ($statuses as $key => $value){
            $status = new DispatchStatus();
            $status->setAlias($key);
            $status->setName($value);
            if ($key == 'raw' || $key == 'ready'){
                $status->setEditable(true);
            }

            $manager->persist($status);
            $manager->flush();
        }



    }
}
