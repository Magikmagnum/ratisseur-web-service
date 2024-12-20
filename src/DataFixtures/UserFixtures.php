<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $passwordHasher = new UserPasswordHasherInterface();
        for ($i = 0; $i < 10; $i++) {
            $user = (new User())
                ->setEmail("user$i@test.com")
                ->setPassword($passwordHasher("coucou comment tu vas"));
            $manager->persist($user);
        }
        $manager->flush();
    }
}
