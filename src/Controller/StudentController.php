<?php

namespace App\Controller;

use App\Entity\Classroom;
use App\Entity\Student;
use App\Entity\Enrollment;
use App\Entity\SchoolYear;
use App\Repository\StudentRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Validator\Constraints\Json;

#[Route('/api/students')]
final class StudentController extends AbstractController
{
    #[Route('', methods: ['GET'])]
    public function index(StudentRepository $repo): JsonResponse
    {
        return $this->json($repo->findAll(), 200, [], ['groups' => 'student:read']);
    }

    #[Route('/{id}', methods: ['GET'])]
    public function show(Student $student) : JsonResponse
    {
        return $this->json($student, 200, [], ['groups' => 'student:read']);
    }

    #[Route('', methods: ['POST'])]
    public function create(Request $request, EntityManagerInterface $em) : JsonResponse
    {

        $data = json_decode($request->getContent(), true);
        
        $student = new Student();
        $student->setFirstname($data['firstname']);
        $student->setLastname($data['lastname']);
        $student->setBirthDate(new \DateTime($data['birthDate']));
        $student->setGender($data['gender']);
        $student->setAddress($data['address']);
        $student->setPhone($data['phone']);
        $student->setEmail($data['email']);
        $student->setRegistrationDate(new \DateTime());
        $student->setStatus($data['status']);

        $em->persist($student);
        $em->flush();

        return $this->json($student, 201);
    }

    #[Route('/{id}', methods: ['PUT'])]
    public function edit(Student $student, Request $request, EntityManagerInterface $em) : JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $student->setFirstname($data['firstname']);
        $student->setLastname($data['lastname']);
        $student->setBirthDate(new \DateTime($data['birthDate']));
        $student->setGender($data['gender']);
        $student->setAddress($data['address']);
        $student->setPhone($data['phone']);
        $student->setEmail($data['email']);
        $student->setRegistrationDate(new \DateTime());
        $student->setStatus($data['status']);
        $student->setMedicalNote($data['medicalNote']);

        $em->flush();
        return $this->json($student, 201);
    }

    #[Route('/{id}', methods: ['DELETE'])]
    public function delete(Student $student, EntityManagerInterface $em) : JsonResponse
    {

        $em->remove($student);
        $em->flush();

        return $this->json(['Student deleted'], 200);
    }
    /*
    #[Route('/register', methods: ['POST'])]
    public function register(
        Request $request,
        EntityManagerInterface $em
    ): JsonResponse {

        $data = json_decode($request->getContent(), true);

        $classroom = $em->getRepository(Classroom::class)->find($data['classroom_id']);
        $schoolYear = $em->getRepository(SchoolYear::class)->find($data['school_year_id']);

        if (!$classroom || !$schoolYear) {
            return $this->json([
                'error' => 'Invalid classroom or school year'
            ], 400);
        }

        // =========================
        // 2. Créer Student
        // =========================

        $student = new Student();
        $student->setFirstname($data['firstname']);
        $student->setLastname($data['lastname']);
        $student->setBirthDate(new \DateTime($data['birthDate']));
        $student->setGender($data['gender']);
        $student->setAddress($data['address']);
        $student->setPhone($data['phone']);
        $student->setEmail($data['email']);
        $student->setRegistrationDate(new \DateTime());
        $student->setStatus($data['status']);
        $student->setMedicalNote($data['medicalNote'] ?? null);

        // =========================
        // 3. Créer Enrollment
        // =========================

        $enrollment = new Enrollment();
        $enrollment->setStudent($student);
        $enrollment->setClassroom($classroom);
        $enrollment->setSchoolYear($schoolYear);
        $enrollment->setEnrollmentDate(new \DateTime());
        $enrollment->setStatus('active');

        // =========================
        // 4. Persist
        // =========================

        $em->persist($student);
        $em->persist($enrollment);
        $em->flush();

        // =========================
        // 5. Réponse
        // =========================

        return $this->json([
            'message' => 'Student registered successfully',
            'student_id' => $student->getId(),
            'enrollment_id' => $enrollment->getId()
        ], 201);
    }


    #[Route('/re-enroll', methods: ['POST'])]
    public function reEnroll(Request $request, EntityManagerInterface $em): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        // =========================
        // 1. Récupérer les entités
        // =========================

        $student = $em->getRepository(Student::class)->find($data['student_id']);
        $classroom = $em->getRepository(Classroom::class)->find($data['classroom_id']);
        $schoolYear = $em->getRepository(SchoolYear::class)->find($data['school_year_id']);

        if (!$student || !$classroom || !$schoolYear) {
            return $this->json([
                'error' => 'Invalid data'
            ], 400);
        }

        // =========================
        // 2. Vérifier double inscription
        // =========================

        $existing = $em->getRepository(Enrollment::class)->findOneBy([
            'student' => $student,
            'schoolYear' => $schoolYear
        ]);

        if ($existing) {
            return $this->json([
                'error' => 'Student already enrolled for this school year'
            ], 400);
        }

        // =========================
        // 3. Créer Enrollment
        // =========================

        $enrollment = new Enrollment();
        $enrollment->setStudent($student);
        $enrollment->setClassroom($classroom);
        $enrollment->setSchoolYear($schoolYear);
        $enrollment->setEnrollmentDate(new \DateTime());
        $enrollment->setStatus($data['status'] ?? 'active');

        // =========================
        // 4. Persist
        // =========================

        $em->persist($enrollment);
        $em->flush();

        return $this->json([
            'message' => 'Student re-enrolled successfully',
            'student_id' => $student->getId(),
            'enrollment_id' => $enrollment->getId()
        ], 201);
    }*/
}

