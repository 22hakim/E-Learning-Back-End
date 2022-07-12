<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\{User, Article};
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{

    private $hasher;

    public function __construct( UserPasswordHasherInterface $userPasswordEncoder)
    {
        $this->hasher = $userPasswordEncoder;
    }
    public function load(ObjectManager $manager): void
    {
        $fake = Factory::create();

        for ($i = 0; $i < 4; $i++) {

            $user = new User();

            $passhash = $this->hasher->encodePassword($user, $data->getPlainPassword());

            $user->setPassword('password')
                ->setEmail($fake->email())
                ->setStatus($fake->boolean())
                ->setAge($fake->numberBetween(18, 72))
                ->setRoles(['ROLE_USER']);

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
