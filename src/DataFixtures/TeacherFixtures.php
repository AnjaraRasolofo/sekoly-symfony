<?php

namespace App\DataFixtures;

use App\Entity\Teacher;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class TeacherFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $teachers = [
            ['firstname' => 'Jean', 'lastname' => 'Rakoto', 'speciality' => 'Mathématiques'],
            ['firstname' => 'Marie', 'lastname' => 'Rasoa', 'speciality' => 'Français'],
            ['firstname' => 'Andry', 'lastname' => 'Randria', 'speciality' => 'Physique'],
            ['firstname' => 'Lalao', 'lastname' => 'Rabe', 'speciality' => 'SVT'],
            ['firstname' => 'Hery', 'lastname' => 'Rakotondrabe', 'speciality' => 'Histoire-Géo'],
            ['firstname' => 'Fara', 'lastname' => 'Rasoanirina', 'speciality' => 'Anglais'],
            ['firstname' => 'Tojo', 'lastname' => 'Raveloson', 'speciality' => 'Informatique'],
            ['firstname' => 'Noro', 'lastname' => 'Razafindrakoto', 'speciality' => 'Philosophie'],
        ];

        foreach ($teachers as $data) {
            $teacher = new Teacher();

            $teacher->setFirstname($data['firstname']);
            $teacher->setLastname($data['lastname']);
            $teacher->setSpeciality($data['speciality']);

            // téléphone réaliste Madagascar
            $teacher->setPhone('03' . rand(2000000, 9999999));

            // email généré automatiquement
            $email = strtolower($data['firstname'] . '.' . $data['lastname']) . '@ecole.mg';
            $teacher->setEmail($email);

            // date d'embauche entre 2015 et aujourd'hui
            $teacher->setHireDate(
                (new \DateTimeImmutable())
                    ->setTimestamp(rand(strtotime('2015-01-01'), time()))
            );

            $manager->persist($teacher);
        }

        $manager->flush();
    }
}