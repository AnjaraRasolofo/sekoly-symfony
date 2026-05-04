<?php

namespace App\DataFixtures;

use App\Entity\SchoolYear;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class SchoolYearFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $years = [
            ['label' => '2019-2020', 'start' => '2019-09-01', 'end' => '2020-07-15', 'status' => 'finished'],
            ['label' => '2020-2021', 'start' => '2020-09-01', 'end' => '2021-07-15', 'status' => 'finished'],
            ['label' => '2021-2022', 'start' => '2021-09-01', 'end' => '2022-07-15', 'status' => 'finished'],
            ['label' => '2022-2023', 'start' => '2022-09-01', 'end' => '2023-07-15', 'status' => 'finished'],
            ['label' => '2023-2024', 'start' => '2023-09-01', 'end' => '2024-07-15', 'status' => 'finished'],
            ['label' => '2024-2025', 'start' => '2024-09-01', 'end' => '2025-07-15', 'status' => 'current'],
            ['label' => '2025-2026', 'start' => '2025-09-01', 'end' => '2026-07-15', 'status' => 'planned'],
        ];

        foreach ($years as $data) {
            $schoolYear = new SchoolYear();

            $schoolYear->setYearLabel($data['label']);
            $schoolYear->setStartDate(new \DateTime($data['start']));
            $schoolYear->setEndDate(new \DateTime($data['end']));
            $schoolYear->setStatus($data['status']);

            $manager->persist($schoolYear);
        }

        $manager->flush();
    }
}