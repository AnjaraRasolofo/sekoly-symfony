<?php

namespace App\Controller;

use App\Entity\Course;
use App\Repository\ClassroomRepository;
use App\Repository\CourseRepository;
use App\Repository\SchoolYearRepository;
use App\Repository\SubjectRepository;
use App\Repository\TeacherRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class CourseController extends AbstractController
{
    #[Route('/api/courses', methods: ['GET'])]
    public function index(CourseRepository $repo): Response
    {
        $courses = $repo->findAll();

        return $this->json($courses, 200);
    }

    public function create(Request $request, EntityManagerInterface $em, TeacherRepository $teacherRepo, 
                            SubjectRepository $subjectRepo, ClassroomRepository $classroomRepo, 
                            SchoolYearRepository $schoolYearRepo): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        if(!$data) 
        {
            return $this->json(['message' => 'JSON invalide'], 400);
        }

        $teacher = $teacherRepo->find($data['teacherId'] ?? null);
        $subject = $subjectRepo->find($data['subjectId'] ?? null);
        $classroom = $classroomRepo->find($data['classroomId'] ?? null);
        $schoolYear = $schoolYearRepo->find($data['schoolYearId'] ?? null);

        if (!$teacher) {
            return $this->json([
                'message' => 'Enseignant introuvable.'
            ], 404);
        }

        if (!$subject) {
            return $this->json([
                'message' => 'Matière introuvable.'
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

        $course = new Course();
        /*$course->setTeacher($teacher);
        $course->setSubject($subject);
        $course->setClassroom($classroom);
        $course->setSchoolYear($schoolYear);
        $course->setCoefficient($data['coefficient']);
        //$course->set*/
        
        return $this->json($course, 201);
    }
}
