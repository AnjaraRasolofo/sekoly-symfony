<?php

namespace App\Entity;

use App\Repository\SchoolYearRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Attribute\Groups;
use Symfony\Component\Serializer\Attribute\Ignore;

#[ORM\Entity(repositoryClass: SchoolYearRepository::class)]
class SchoolYear
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['schoolyear:read', 'enrollment:read'])]
    private ?int $id = null;

    #[ORM\Column(length: 20)]
    #[Groups(['schoolyear:read', 'enrollment:read'])]
    private ?string $yearLabel = null;

    /**
     * @var Collection<int, Enrollment>
     */
    #[ORM\OneToMany(targetEntity: Enrollment::class, mappedBy: 'schoolYear', orphanRemoval: true)]
    #[Ignore]
    private Collection $enrollments;

    #[ORM\Column]
    private ?\DateTime $startDate = null;

    #[ORM\Column]
    private ?\DateTime $endDate = null;

    #[ORM\Column(length: 20)]
    private ?string $status = null;

    public function __construct()
    {
        $this->enrollments = new ArrayCollection();
    }
    
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getYearLabel(): ?string
    {
        return $this->yearLabel;
    }

    public function setYearLabel(string $yearLabel): self
    {
        $this->yearLabel = $yearLabel;

        return $this;
    }

    /**
     * @return Collection<int, Enrollment>
     */
    public function getEnrollments(): Collection
    {
        return $this->enrollments;
    }

    public function addEnrollment(Enrollment $enrollment): static
    {
        if (!$this->enrollments->contains($enrollment)) {
            $this->enrollments->add($enrollment);
            $enrollment->setSchoolYear($this);
        }

        return $this;
    }

    public function removeEnrollment(Enrollment $enrollment): static
    {
        if ($this->enrollments->removeElement($enrollment)) {
            // set the owning side to null (unless already changed)
            if ($enrollment->getSchoolYear() === $this) {
                $enrollment->setSchoolYear(null);
            }
        }

        return $this;
    }

    public function getStartDate(): ?\DateTime
    {
        return $this->startDate;
    }

    public function setStartDate(\DateTime $startDate): static
    {
        $this->startDate = $startDate;

        return $this;
    }

    public function getEndDate(): ?\DateTime
    {
        return $this->endDate;
    }

    public function setEndDate(\DateTime $endDate): static
    {
        $this->endDate = $endDate;

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
}
