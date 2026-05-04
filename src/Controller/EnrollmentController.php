<?php

namespace App\Controller;

use App\Entity\Enrollment;
use App\Entity\Student;
use App\Entity\Classroom;
use App\Entity\SchoolYear;
use App\Repository\StudentRepository;
use App\Repository\ClassroomRepository;
use App\Repository\SchoolYearRepository;
use App\Repository\EnrollmentRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;


#[Route('/api/enrollments')]
class EnrollmentController extends AbstractController
{
    #[Route('', methods: ['GET'])]
    public function index(EnrollmentRepository $repo): JsonResponse
    {
        return $this->json($repo->findAll(), 200, [], ['groups' => ['enrollment:read']]);
    }

    #[Route('/{id}', methods: ['GET'])]
    public function show(Enrollment $enrollment): JsonResponse
    {
        return $this->json($enrollment, 200, [], ['groups' => ['enrollment:read']]);
    }

    #[Route('', name: 'enrollment_create', methods: ['POST'])]
    public function create(
        Request $request,
        EntityManagerInterface $em,
        StudentRepository $studentRepository,
        ClassroomRepository $classroomRepository,
        SchoolYearRepository $schoolYearRepository
    ): JsonResponse 
    {
        $data = json_decode($request->getContent(), true);

        if (!$data) {
            return $this->json([
                'message' => 'JSON invalide.'
            ], 400);
        }

        $student = $studentRepository->find($data['studentId'] ?? null);
        $classroom = $classroomRepository->find($data['classroomId'] ?? null);
        $schoolYear = $schoolYearRepository->find($data['schoolYearId'] ?? null);

        if (!$student) {
            return $this->json([
                'message' => 'Élève introuvable.'
            ], 404);
        }

        if (!$classroom) {
            return $this->json([
                'message' => 'Classe introuvable.'
            ], 404);
        }

        if (!$schoolYear) {
            return $this->json([
                'message' => 'Année scolaire introuvable.'
            ], 404);
        }

        $enrollment = new Enrollment();
        $enrollment->setStudent($student);
        $enrollment->setClassroom($classroom);
        $enrollment->setSchoolYear($schoolYear);

        $enrollmentDate = $data['enrollmentDate'] ?? date('Y-m-d');
        $enrollment->setEnrollmentDate(new \DateTime($enrollmentDate));

        $enrollment->setStatus($data['status'] ?? 'active');

        $em->persist($enrollment);
        $em->flush();

        return $this->json([
            'message' => 'Inscription créée avec succès.',
            'enrollment' => [
                'id' => $enrollment->getId(),
                'student' => [
                    'id' => $student->getId(),
                    'firstname' => $student->getFirstname(),
                    'lastname' => $student->getLastname(),
                ],
                'classroom' => [
                    'id' => $classroom->getId(),
                    'classname' => $classroom->getClassname(),
                    'level' => $classroom->getLevel(),
                ],
                'schoolYear' => [
                    'id' => $schoolYear->getId(),
                    'yearLabel' => $schoolYear->getYearLabel(),
                ],
                'enrollmentDate' => $enrollment->getEnrollmentDate()->format('Y-m-d'),
                'status' => $enrollment->getStatus(),
            ]
        ], 201);
    }

    #[Route('/{id}', methods: ['PUT'])]
    public function update(
        Enrollment $enrollment,
        Request $request,
        EntityManagerInterface $em,
        StudentRepository $studentRepository,
        ClassroomRepository $classroomRepository,
        SchoolYearRepository $schoolYearRepository
    ): JsonResponse {
        $data = json_decode($request->getContent(), true);

        if (!$data) {
            return $this->json([
                'message' => 'JSON invalide.'
            ], 400);
        }

        if (isset($data['studentId'])) {
            $student = $studentRepository->find($data['studentId']);

            if (!$student) {
                return $this->json([
                    'message' => 'Élève introuvable.'
                ], 404);
            }

            $enrollment->setStudent($student);
        }

        if (isset($data['classroomId'])) {
            $classroom = $classroomRepository->find($data['classroomId']);

            if (!$classroom) {
                return $this->json([
                    'message' => 'Classe introuvable.'
                ], 404);
            }

            $enrollment->setClassroom($classroom);
        }

        if (isset($data['schoolYearId'])) {
            $schoolYear = $schoolYearRepository->find($data['schoolYearId']);

            if (!$schoolYear) {
                return $this->json([
                    'message' => 'Année scolaire introuvable.'
                ], 404);
            }

            $enrollment->setSchoolYear($schoolYear);
        }

        if (isset($data['enrollmentDate'])) {
            $enrollment->setEnrollmentDate(new \DateTime($data['enrollmentDate']));
        }

        if (isset($data['status'])) {
            $enrollment->setStatus($data['status']);
        }

        $em->flush();

        return $this->json([
            'message' => 'Inscription modifiée avec succès.',
            'enrollment' => [
                'id' => $enrollment->getId(),
                'student' => [
                    'id' => $enrollment->getStudent()->getId(),
                    'firstname' => $enrollment->getStudent()->getFirstname(),
                    'lastname' => $enrollment->getStudent()->getLastname(),
                ],
                'classroom' => [
                    'id' => $enrollment->getClassroom()->getId(),
                    'classname' => $enrollment->getClassroom()->getClassname(),
                    'level' => $enrollment->getClassroom()->getLevel(),
                ],
                'schoolYear' => [
                    'id' => $enrollment->getSchoolYear()->getId(),
                    'yearLabel' => $enrollment->getSchoolYear()->getYearLabel(),
                ],
                'enrollmentDate' => $enrollment->getEnrollmentDate()->format('Y-m-d'),
                'status' => $enrollment->getStatus(),
            ]
        ], 200);
    }
    

    #[Route('/{id}', methods: ['DELETE'])]
    public function delete(
        Enrollment $enrollment,
        EntityManagerInterface $em
    ): JsonResponse {

        $em->remove($enrollment);
        $em->flush();

        return $this->json(['message' => 'Enrollment deleted']);
    }

    /*#[Route('', methods: ['POST'])]
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
    }*/
    
}
