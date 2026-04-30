<?php

namespace App\Controller;

use App\Entity\Enrollment;
use App\Entity\Student;
use App\Entity\Classroom;
use App\Entity\SchoolYear;
use App\Repository\EnrollmentRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;


#[Route('/api/enrollments')]
class EnrollmentController extends AbstractController
{
    // GET /api/enrollments
    #[Route('', methods: ['GET'])]
    public function index(EnrollmentRepository $repo): JsonResponse
    {
        return $this->json($repo->findAll(), 200, [], ['groups' => ['enrollment:read']]);
    }

    // GET /api/enrollments/{id}
    #[Route('/{id}', methods: ['GET'])]
    public function show(Enrollment $enrollment): JsonResponse
    {
        return $this->json($enrollment, 200, [], ['groups' => ['enrollment:read']]);
    }

    // POST /api/enrollments
    #[Route('', methods: ['POST'])]
    public function create(
        Request $request,
        EntityManagerInterface $em
    ): JsonResponse {

        $data = json_decode($request->getContent(), true);

        $student = $em->getRepository(Student::class)->find($data['student_id']);
        $classroom = $em->getRepository(Classroom::class)->find($data['classroom_id']);
        $schoolYear = $em->getRepository(SchoolYear::class)->find($data['schoolyear_id']);

        if (!$student || !$classroom || !$schoolYear) {
            return $this->json([
                'error' => 'Invalid student, classroom or school year'
            ], 400);
        }

        $enrollment = new Enrollment();
        $enrollment->setStudent($student);
        $enrollment->setClassroom($classroom);
        $enrollment->setSchoolYear($schoolYear);
        $enrollment->setEnrollmentDate(new \DateTime());
        $enrollment->setStatus('active');

        $em->persist($enrollment);
        $em->flush();

        return $this->json($enrollment, 201, [], ['groups' => ['enrollments']]);
    }

    // DELETE /api/enrollments/{id}
    #[Route('/{id}', methods: ['DELETE'])]
    public function delete(
        Enrollment $enrollment,
        EntityManagerInterface $em
    ): JsonResponse {

        $em->remove($enrollment);
        $em->flush();

        return $this->json(['message' => 'Enrollment deleted']);
    }
}
