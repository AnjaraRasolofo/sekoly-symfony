<?php

namespace App\Entity;

use App\Repository\EnrollmentRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Attribute\Groups;
use Symfony\Component\Serializer\Attribute\Ignore;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

#[ORM\Entity(repositoryClass: EnrollmentRepository::class)]
#[UniqueEntity(
    fields:['student', 'schoolYear'],
    message: 'Cet élève est déjà inscrit pour cette année scolaire.'
)]
class Enrollment
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['enrollment:read'])]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'enrollments')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['enrollment:read'])]
    private ?Student $student = null;

    #[ORM\ManyToOne(inversedBy: 'enrollments')]
    #[ORM\JoinColumn(nullable: false)]
    //-- #[Ignore]
    #[Groups(['enrollment:read'])]
    private ?Classroom $classroom = null;

    #[ORM\ManyToOne(inversedBy: 'enrollments')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['enrollment:read'])]
    private ?SchoolYear $schoolYear = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    #[Groups(['enrollment:read'])]
    private ?\DateTime $enrollmentDate = null;

    #[ORM\Column(length: 50)]
    #[Groups(['enrollment:read'])]
    private ?string $status = 'active';

    #[ORM\Column(type: Types::DECIMAL, precision: 10, scale: 2)]
    #[Groups(['enrollment:read'])]
    private ?string $totalFee = '0.00';

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    #[Groups(['enrollment:read'])]
    private ?\DateTime $paymentDeadline = null;

    #[ORM\Column(length: 50)]
    #[Groups(['enrollment:read'])]
    private ?string $paymentStatus = 'unpaid'; // unpaid, partial, paid, late, cancelled

    #[ORM\OneToMany(mappedBy: 'enrollment', targetEntity: Payment::class, orphanRemoval: true)]
    private Collection $payments;

    public function __construct()
    {
        $this->payments = new ArrayCollection();
        $this->enrollmentDate = new \DateTime();
    }
    
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getStudent(): ?Student
    {
        return $this->student;
    }

    public function setStudent(?Student $student): static
    {
        $this->student = $student;

        return $this;
    }

    public function getClassroom(): ?Classroom
    {
        return $this->classroom;
    }

    public function setClassroom(?Classroom $classroom): static
    {
        $this->classroom = $classroom;

        return $this;
    }

    public function getSchoolYear(): ?SchoolYear
    {
        return $this->schoolYear;
    }

    public function setSchoolYear(?SchoolYear $schoolYear): static
    {
        $this->schoolYear = $schoolYear;

        return $this;
    }

    public function getEnrollmentDate(): ?\DateTime
    {
        return $this->enrollmentDate;
    }

    public function setEnrollmentDate(\DateTime $enrollmentDate): static
    {
        $this->enrollmentDate = $enrollmentDate;

        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(string $status): static
    {
        $this->status = $status;

        return $this;
    }

    public function getTotalFee(): ?string
    {
        return $this->totalFee;
    }

    public function setTotalFee(string $totalFee): static
    {
        $this->totalFee = $totalFee;

        return $this;
    }

    public function getPaymentDeadline(): ?\DateTime
    {
        return $this->paymentDeadline;
    }

    public function setPaymentDeadline(?\DateTime $paymentDeadline): static
    {
        $this->paymentDeadline = $paymentDeadline;

        return $this;
    }

    public function getPaymentStatus(): ?string
    {
        return $this->paymentStatus;
    }

    public function setPaymentStatus(string $paymentStatus): static
    {
        $this->paymentStatus = $paymentStatus;

        return $this;
    }

    public function getPayments(): Collection
    {
        return $this->payments;
    }

    public function addPayment(Payment $payment): static
    {
        if (!$this->payments->contains($payment)) {
            $this->payments->add($payment);
            $payment->setEnrollment($this);
        }

        return $this;
    }

    public function removePayment(Payment $payment): static
    {
        if ($this->payments->removeElement($payment)) {
            if ($payment->getEnrollment() === $this) {
                $payment->setEnrollment(null);
            }
        }

        return $this;
    }

    public function getTotalPaid(): float
    {
        $total = 0;

        foreach ($this->payments as $payment) {
            if ($payment->getStatus() === 'paid') {
                $total += (float) $payment->getAmount();
            }
        }

        return $total;
    }

    public function getRemainingAmount(): float
    {
        return (float) $this->totalFee - $this->getTotalPaid();
    }

    public function isLate(): bool
    {
        return $this->paymentDeadline !== null
            && new \DateTime() > $this->paymentDeadline
            && $this->getRemainingAmount() > 0;
    }
}
