<?php

namespace App\Controller;

use App\Entity\SchoolYear;
use App\Repository\SchoolYearRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api/school-years')]
class SchoolYearController extends AbstractController
{
    #[Route('', methods: ['GET'])]
    public function index(SchoolYearRepository $repo): JsonResponse
    {
        return $this->json($repo->findAll());
    }

    #[Route('/{id}', methods: ['GET'])]
    public function show(SchoolYear $schoolYear): JsonResponse
    {
        return $this->json($schoolYear);
    }

    #[Route('', methods: ['POST'])]
    public function create(Request $request, EntityManagerInterface $em): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $schoolYear = new SchoolYear();
        $schoolYear->setYearLabel($data['yearLabel']); // ex: 2025-2026

        $em->persist($schoolYear);
        $em->flush();

        return $this->json($schoolYear, 201);
    }

    #[Route('/{id}', methods: ['PUT'])]
    public function update(
        SchoolYear $schoolYear,
        Request $request,
        EntityManagerInterface $em
    ): JsonResponse {

        $data = json_decode($request->getContent(), true);

        $schoolYear->setYearLabel($data['yearLabel']);

        $em->flush();

        return $this->json($schoolYear);
    }

    #[Route('/{id}', methods: ['DELETE'])]
    public function delete(SchoolYear $schoolYear, EntityManagerInterface $em): JsonResponse
    {
        $em->remove($schoolYear);
        $em->flush();

        return $this->json(['message' => 'School year deleted']);
    }
}
