<?php

namespace TortillatorAPI\TortillatorBundle\Tests\Fixtures\Entity;

use TortillatorAPI\TortillatorBundle\Entity\User;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;


class LoadUserData implements FixtureInterface
{
    static public $users = array();

    public function load(ObjectManager $manager)
    {
        $user = new User();
        $user->setUsername('userTest');
        $user->setPassword('Test');
        $user->setEmail('userTest@gmail.com');
        $user->setCity('TestCity');

        $manager->persist($user);
        $manager->flush();

        self::$users[] = $user;
    }
}
