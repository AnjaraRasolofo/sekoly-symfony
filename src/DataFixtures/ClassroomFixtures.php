<?php

namespace App\DataFixtures;

use App\Entity\Classroom;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class ClassroomFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        // --------------------
        // PRIMAIRE (PS -> CM2)
        // capacité: 30 à 40
        // --------------------
        $primaireLevels = [
            'PS', 'MS', 'GS',
            'CP', 'CE1', 'CE2',
            'CM1', 'CM2'
        ];

        foreach ($primaireLevels as $level) {
            $classroom = new Classroom();
            $classroom->setLevel('primaire');
            $classroom->setClassname($level);
            $classroom->setCapacity(rand(30, 40));

            $manager->persist($classroom);
        }

        // --------------------
        // COLLÈGE (6ème -> 3ème)
        // capacité: 25 à 35
        // --------------------
        $collegeLevels = [
            '6èmeA', '6èmeB', '5èmeA','5èmeB', '4èmeA', '4èmeB', '3èmeA', '3èmeB'
        ];

        foreach ($collegeLevels as $level) {
            $classroom = new Classroom();
            $classroom->setLevel('college');
            $classroom->setClassname($level);
            $classroom->setCapacity(rand(25, 35));

            $manager->persist($classroom);
        }

        // --------------------
        // LYCÉE (2nd -> Terminale)
        // capacité: 20 à 30
        // --------------------
        $lyceeLevels = [
            '2nd', '1ère', 'Terminale'
        ];

        foreach ($lyceeLevels as $level) {
            $classroom = new Classroom();
            $classroom->setLevel('lycee');
            $classroom->setClassname($level);
            $classroom->setCapacity(rand(20, 30));

            $manager->persist($classroom);
        }

        $manager->flush();
    }
}
