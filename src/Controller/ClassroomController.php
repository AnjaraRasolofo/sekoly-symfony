<?php

namespace App\Controller;

use App\Entity\Classroom;
use App\Repository\ClassroomRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api/classrooms')]
class ClassroomController extends AbstractController
{
    #[Route('', methods: ['GET'])]
    public function index(ClassroomRepository $repo): JsonResponse
    {
        $classrooms = $repo->findAll();
        
        return $this->json($classrooms, 200, [], [
            'groups' => 'classroom:read'
        ]);
    }

    #[Route('/{id}', methods: ['GET'])]
    public function show(Classroom $classroom): JsonResponse
    {
        return $this->json($classroom, 200, [], [
            'groups' => 'classroom:read'
        ]);
    }

    #[Route('', methods: ['POST'])]
    public function create(Request $request, EntityManagerInterface $em): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $classroom = new Classroom();
        $classroom->setClassName($data['classname']);
        $classroom->setLevel($data['level']);
        $classroom->setCapacity($data['capacity']);

        $em->persist($classroom);
        $em->flush();

        return $this->json($classroom, 201, [], ['groups' => 'classroom:read']);
    }

    #[Route('/{id}', methods: ['PUT'])]
    public function update(
        Classroom $classroom,
        Request $request,
        EntityManagerInterface $em
    ): JsonResponse {

        $data = json_decode($request->getContent(), true);

        $classroom->setClassName($data['classname']);
        $classroom->setLevel($data['level']);
        $classroom->setCapacity($data['capacity']);

        $em->flush();

        return $this->json($classroom);
    }

    #[Route('/{id}', methods: ['DELETE'])]
    public function delete(Classroom $classroom, EntityManagerInterface $em): JsonResponse
    {
        $em->remove($classroom);
        $em->flush();

        return $this->json(['message' => 'Classroom deleted']);
    }
}
