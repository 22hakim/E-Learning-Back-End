<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use App\Entity\{User, Article};
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixtures extends Fixture
{
    private UserPasswordHasherInterface $hasher;

    public function __construct(UserPasswordHasherInterface $hasher)
    {
        $this->hasher = $hasher;
    }

    public function load(ObjectManager $manager): void
    {
        $fake = Factory::create();

        for ($i = 0; $i < 3; $i++) {

            $user = new User();

            $passhash = $this->hasher->hashPassword($user, 'password');

            $user->setPassword($passhash)
                ->setEmail($fake->email);

            $manager->persist($user);

            for ($i = 0; $i < random_int(1, 3); $i++) {

                $article = new Article();
                $article->setAuthor($user)
                    ->setTitle($fake->text(50))
                    ->setContent($fake->text(200));

                $manager->persist($article);
            }
        }

        $manager->flush();
    }
}
