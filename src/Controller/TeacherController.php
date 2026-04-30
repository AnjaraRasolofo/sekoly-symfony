<?php

namespace App\Controller;

use App\Entity\Teacher;
use App\Repository\TeacherRepository;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api/teachers')]
final class TeacherController extends AbstractController
{
    #[Route('/', methods: ['GET'])]
    public function index(TeacherRepository $repo): JsonResponse
    {
        $teachers = $repo->findAll();

        return $this->json($teachers, 200);
    }

    #[Route('/{$id}', methods: ['GET'])]
    public function show($id, TeacherRepository $repo): JsonResponse
    {
        $teacher = $repo->findById($id);

        return $this->json($teacher);
    }

    #[Route('', methods: ['POST'])]
    public function create(Request $request, EntityManagerInterface $em) : JsonResponse
    {

        $data = json_decode($request->getContent(), true);
        
        $teacher = new Teacher();
        $teacher->setFirstname($data['firstname']);
        $teacher->setLastname($data['lastname']);
        $teacher->setPhone($data['phone']);
        $teacher->setEmail($data['email']);
        $teacher->setSpeciality($data['speciality']);
        $teacher->setHireDate(new \DateTimeImmutable());

        $em->persist($teacher);
        $em->flush();

        return $this->json($teacher, 201);
    }

    #[Route('/{id}', methods: ['PUT'])]
    public function edit(Teacher $teacher, Request $request, EntityManagerInterface $em) : JsonResponse
    {

        $data = json_decode($request->getContent(), true);
        
        $teacher->setFirstname($data['firstname']);
        $teacher->setLastname($data['lastname']);
        $teacher->setPhone($data['phone']);
        $teacher->setEmail($data['email']);
        $teacher->setSpeciality($data['speciality']);
        $teacher->setHireDate(new \DateTimeImmutable());

        $em->flush();

        return $this->json($teacher, 201);
    }

    #[Route('/{id}', methods: ['DELETE'])]
    public function delete(Teacher $teacher, EntityManagerInterface $em) : JsonResponse
    {

        $em->remove($teacher);
        $em->flush();

        return $this->json(['Student deleted'], 200);
    }
}
