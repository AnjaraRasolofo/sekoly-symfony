<?php

namespace App\Controller;

use App\Entity\Enrollment;
use App\Entity\Payment;
use App\Repository\PaymentRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api/payments')]
class PaymentController extends AbstractController
{

    #[Route('', name: 'api_payments_list', methods: ['GET'])]
    public function getAll(PaymentRepository $paymentRepository): JsonResponse
    {
        $payments = $paymentRepository->findBy([], ['paymentDate' => 'DESC']);

        $data = [];

        foreach ($payments as $payment) {
            $data[] = [
                'id' => $payment->getId(),
                'amount' => $payment->getAmount(),
                'paymentDate' => $payment->getPaymentDate()?->format('Y-m-d'),
                'method' => $payment->getMethod(),
                'status' => $payment->getStatus(),
                'reference' => $payment->getReference(),
                'enrollmentId' => $payment->getEnrollment()?->getId(),
            ];
        }

        return $this->json($data);
    }

    #[Route('/enrollment/{id}', name: 'payment_create_from_enrollment', methods: ['POST'])]
    public function createPayment(
        Enrollment $enrollment,
        Request $request,
        EntityManagerInterface $em
    ): JsonResponse {
        $data = json_decode($request->getContent(), true);

        if (!isset($data['amount']) || (float) $data['amount'] <= 0) {
            return $this->json([
                'message' => 'Le montant du paiement est obligatoire.'
            ], 400);
        }

        $payment = new Payment();
        $payment->setEnrollment($enrollment);
        $payment->setAmount((string) $data['amount']);
        $payment->setPaymentDate(new \DateTime());
        $payment->setMethod($data['method'] ?? 'cash');
        $payment->setStatus('paid');
        $payment->setReference($data['reference'] ?? null);
        $payment->setNote($data['note'] ?? null);

        $em->persist($payment);
        $em->flush();

        $totalFee = (float) $enrollment->getTotalFee();
        $totalPaid = $enrollment->getTotalPaid();
        $remaining = $totalFee - $totalPaid;

        if ($remaining <= 0) {
            $enrollment->setPaymentStatus('paid');
        } elseif ($enrollment->isLate()) {
            $enrollment->setPaymentStatus('late');
        } else {
            $enrollment->setPaymentStatus('partial');
        }

        $em->flush();

        return $this->json([
            'message' => 'Paiement enregistré avec succès.',
            'payment' => [
                'id' => $payment->getId(),
                'amount' => $payment->getAmount(),
                'method' => $payment->getMethod(),
                'status' => $payment->getStatus(),
                'reference' => $payment->getReference(),
            ],
            'summary' => [
                'total_fee' => $totalFee,
                'total_paid' => $totalPaid,
                'remaining_amount' => max($remaining, 0),
                'payment_status' => $enrollment->getPaymentStatus(),
                'is_late' => $enrollment->isLate(),
            ]
        ], 201);
    }

    #[Route('/enrollment/{id}/summary', name: 'payment_summary_by_enrollment', methods: ['GET'])]
    public function paymentSummary(Enrollment $enrollment): JsonResponse
    {
        $totalFee = (float) $enrollment->getTotalFee();
        $totalPaid = $enrollment->getTotalPaid();
        $remaining = $totalFee - $totalPaid;

        return $this->json([
            'enrollment_id' => $enrollment->getId(),
            'student' => [
                'id' => $enrollment->getStudent()?->getId(),
            ],
            'total_fee' => $totalFee,
            'total_paid' => $totalPaid,
            'remaining_amount' => max($remaining, 0),
            'payment_status' => $enrollment->getPaymentStatus(),
            'is_late' => $enrollment->isLate(),
            'payments' => array_map(function (Payment $payment) {
                return [
                    'id' => $payment->getId(),
                    'amount' => $payment->getAmount(),
                    'date' => $payment->getPaymentDate()?->format('Y-m-d'),
                    'method' => $payment->getMethod(),
                    'status' => $payment->getStatus(),
                    'reference' => $payment->getReference(),
                ];
            }, $enrollment->getPayments()->toArray())
        ]);
    }
}
