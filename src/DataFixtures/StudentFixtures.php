<?php

namespace App\DataFixtures;

use App\Entity\Student;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class StudentFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $firstnames = [
            'Hery', 'Aina', 'Tiana', 'Fara', 'Toky', 'Mialy', 'Andry',
            'Lova', 'Nantenaina', 'Sarah', 'Fitia', 'Joel', 'Tovo',
            'Miora', 'Kanto', 'Tahina', 'Feno', 'Lalaina', 'Hanta',
            'Mamy', 'Tahiry', 'Soa', 'Faniry', 'Tsiory', 'Rija'
        ];

        $lastnames = [
            'Rakotomalala', 'Razanajatovo', 'Rakotoarisoa', 'Raveloson',
            'Rafanomezantsoa', 'Rakotonirina', 'Randrianarisoa',
            'Raharimalala', 'Rakotondramanana', 'Razanamihaja',
            'Rasoanirina', 'Rakotobe', 'Ramanantsoa', 'Rakotovao',
            'Rafalimanana', 'Razanakolona', 'Rakotondrabe',
            'Rasoanaivo', 'Rakotoniaina', 'Ravelojaona'
        ];

        $addresses = [
            'Antananarivo', 'Ivato', 'Talatamaty', 'Ambohipo',
            'Ankorondrano', 'Mahamasina', 'Isotry',
            'Andavamamba', 'Ankadifotsy', 'Itaosy'
        ];

        for ($i = 1; $i <= 50; $i++) {
            $student = new Student();

            $gender = rand(0, 1) ? 'male' : 'female';

            $student->setFirstname($firstnames[array_rand($firstnames)]);
            $student->setLastname($lastnames[array_rand($lastnames)]);
            $student->setGender($gender);

            $student->setBirthDate(new \DateTime(
                rand(2007, 2009) . '-' . rand(1, 12) . '-' . rand(1, 28)
            ));

            $student->setAddress($addresses[array_rand($addresses)]);

            $student->setPhone('03' . rand(2, 4) . rand(10000000, 99999999));

            $student->setEmail(
                strtolower($student->getFirstname()) . '.' .
                strtolower($student->getLastname()) . $i . '@example.com'
            );

            $student->setRegistrationDate(new \DateTime('2026-03-25'));
            $student->setStatus('active');
            $student->setMedicalNote('None');

            $student->setRegistrationNumber('M' . str_pad($i, 3, '0', STR_PAD_LEFT));

            $manager->persist($student);
        }

        $manager->flush();
    }
}
