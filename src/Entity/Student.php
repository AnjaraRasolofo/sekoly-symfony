<?php

namespace App\Entity;

use App\Repository\StudentRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Attribute\Groups;

#[ORM\Entity(repositoryClass: StudentRepository::class)]
class Student
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['enrollment:read'])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(['enrollment:read'])]
    private ?string $firstname = null;

    #[ORM\Column(length: 255)]
    #[Groups(['enrollment:read'])]
    private ?string $lastname = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $birthDate = null;

    #[ORM\Column(length: 50)]
    private ?string $gender = null;

    #[ORM\Column(length: 255)]
    private ?string $address = null;

    #[ORM\Column(length: 50)]
    private ?string $phone = null;

    #[ORM\Column(length: 255)]
    private ?string $email = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $registrationDate = null;

    #[ORM\Column(length: 50)]
    private ?string $status = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $medicalNote = null;

    #[ORM\Column(length: 50)]
    private ?string $registrationNumber = null;

    /**
     * @var Collection<int, Enrollment>
     */
    /*#[ORM\OneToMany(targetEntity: Enrollment::class, mappedBy: 'student', orphanRemoval: true)]
    private Collection $enrollments;*/

    public function __construct()
    {
        
    }

    // =========================
    // GETTERS / SETTERS
    // =========================

    public function getId(): ?int { return $this->id; }

    public function getFirstname(): ?string { return $this->firstname; }
    public function setFirstname(string $firstname): self {
        $this->firstname = $firstname;
        return $this;
    }

    public function getLastname(): ?string { return $this->lastname; }
    public function setLastname(string $lastname): self {
        $this->lastname = $lastname;
        return $this;
    }

    public function getBirthDate(): ?\DateTimeInterface { return $this->birthDate; }
    public function setBirthDate(\DateTimeInterface $birthDate): self {
        $this->birthDate = $birthDate;
        return $this;
    }

    public function getGender(): ?string { return $this->gender; }
    public function setGender(string $gender): self {
        $this->gender = $gender;
        return $this;
    }

    public function getAddress(): ?string { return $this->address; }
    public function setAddress(string $address): self {
        $this->address = $address;
        return $this;
    }

    public function getPhone(): ?string { return $this->phone; }
    public function setPhone(string $phone): self {
        $this->phone = $phone;
        return $this;
    }

    public function getEmail(): ?string { return $this->email; }
    public function setEmail(string $email): self {
        $this->email = $email;
        return $this;
    }

    public function getRegistrationDate(): ?\DateTimeInterface {
        return $this->registrationDate;
    }
    public function setRegistrationDate(\DateTimeInterface $date): self {
        $this->registrationDate = $date;
        return $this;
    }

    public function getStatus(): ?string { return $this->status; }
    public function setStatus(string $status): self {
        $this->status = $status;
        return $this;
    }

    public function getMedicalNote(): ?string { return $this->medicalNote; }
    public function setMedicalNote(?string $note): self {
        $this->medicalNote = $note;
        return $this;
    }


    /**
     * @return Collection<int, Enrollment>
     */
    /*public function getEnrollments(): Collection
    {
        return $this->enrollments;
    }

    public function addEnrollment(Enrollment $enrollment): static
    {
        if (!$this->enrollments->contains($enrollment)) {
            $this->enrollments->add($enrollment);
            $enrollment->setStudent($this);
        }

        return $this;
    }

    public function removeEnrollment(Enrollment $enrollment): static
    {
        if ($this->enrollments->removeElement($enrollment)) {
            // set the owning side to null (unless already changed)
            if ($enrollment->getStudent() === $this) {
                $enrollment->setStudent(null);
            }
        }

        return $this;
    }*/

    public function getRegistrationNumber(): ?string
    {
        return $this->registrationNumber;
    }

    public function setRegistrationNumber(string $registrationNumber): static
    {
        $this->registrationNumber = $registrationNumber;

        return $this;
    }
}