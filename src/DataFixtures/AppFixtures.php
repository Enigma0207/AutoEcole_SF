<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\User;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use libphonenumber\PhoneNumberUtil;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create();
        $phoneNumberUtil = PhoneNumberUtil::getInstance();

        $users = [];

        for ($i = 0; $i < 50; $i++) {
            $user = new User();
            $user->setFirstname($faker->firstName());
            $user->setLastname($faker->lastName());
            $user->setPhone($faker->phoneNumber());
            $user->setEmail($faker->email());
            $user->setPassword($faker->password());
            $manager->persist($user);
            $users[] = $user;
        }

        // Enregistrez réellement les objets dans la base de données
        $manager->flush();
    }
}
