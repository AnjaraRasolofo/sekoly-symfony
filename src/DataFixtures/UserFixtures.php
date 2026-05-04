<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixtures extends Fixture
{
    private UserPasswordHasherInterface $passwordHasher;

    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;
    }

    public function load(ObjectManager $manager): void
    {
        $users = [
            ['email' => 'admin@agency301.website', 'roles' => ['ROLE_ADMIN']],
            ['email' => 'demo@test.com', 'roles' => ['ROLE_ADMIN']],
            ['email' => 'user@example.com', 'roles' => ['ROLE_USER']],
        ];

        foreach ($users as $data) {
            $user = new User();
            $user->setEmail($data['email']);
            $user->setRoles($data['roles']);

            $hashedPassword = $this->passwordHasher->hashPassword(
                $user,
                'demo123' // mot de passe en clair
            );

            $user->setPassword($hashedPassword);

            $manager->persist($user);
        }

        $manager->flush();
    }
}