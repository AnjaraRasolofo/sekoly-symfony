<?php

namespace App\Controller;

use App\Entity\Subject;
use App\Repository\SubjectRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

final class SubjectController extends AbstractController
{
    #[Route('/api/subjects', methods: ['GET'])]
    public function index(SubjectRepository $repo): JsonResponse
    {
        $subjects = $repo->findAll();

        return $this->json($subjects, 200);
    }

    #[Route('/api/subjects', methods: ['POST'])]
    public function create(Request $request, EntityManagerInterface $em): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        
        $subject = new Subject();
        $subject->setName($data['name']);
        $subject->setCoefficient($data['coefficient']);
        $subject->setDescription($data['description']);

        $em->persist($subject);
        $em->flush();

        return $this->json($subject, 201);
    }

    #[Route('/api/subjects/{id}', methods: ['PUT'])]
    public function edit(Subject $subject, Request $request, EntityManagerInterface $em): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $subject->setName($data['name']);
        $subject->setCoefficient($data['coefficient']);
        $subject->setDescription($data['description']);

        $em->flush();

        return $this->json($subject, 201);
    }

    #[Route('/api/subjects', methods: ['DELETE'])]
    public function delete(Subject $subject, EntityManagerInterface $em): JsonResponse
    {
        $em->remove($subject);
        $em->flush();

        return $this->json(['Subject deleted'], 200);
    }
}
