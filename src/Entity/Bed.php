<?php

namespace App\Entity;

use App\Repository\BedRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Attribute\Groups;
use Symfony\Component\Serializer\Attribute\Ignore;

#[ORM\Entity(repositoryClass: BedRepository::class)]
class Bed
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['bed', 'rooms_and_bed', 'entireBooking'])]
    private ?int $id = null;


    #[ORM\Column]
    #[Groups(['bed', 'rooms_and_bed'])]
    private ?bool $isSittingApart = null;

    #[ORM\Column(type: Types::TEXT)]
    #[Groups(['bed', 'rooms_and_bed', 'entireBooking'])]
    private ?string $state = null;
    //blocked, cleaned, inspected, notcleaned

    #[ORM\ManyToOne(inversedBy: 'beds')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['rooms'])]
    private ?Room $room = null;

    #[ORM\Column(unique: true)]
    #[Groups(['bed', 'rooms_and_bed', 'entireBooking'])]
    private ?int $number = null;

    #[ORM\ManyToOne(inversedBy: 'bedsCleaned')]
    #[Groups(['bed'])]
    private ?User $cleanedBy = null;

    #[ORM\ManyToOne(inversedBy: 'bedsInspected')]
    #[Groups(['bed'])]
    private ?User $inspectedBy = null;

    #[ORM\Column]
    #[Groups(['bed', 'rooms_and_bed'])]
    private ?bool $isDoubleBed = null;

    #[ORM\Column(nullable: true)]
    #[Groups(['bed', 'rooms_and_bed'])]
    private ?string $bedShape = null;
// topBed,bottomBed,singleBed
    #[ORM\Column]
    #[Groups(['bed', 'rooms_and_bed'])]
    private ?bool $hasLamp = null;

    #[ORM\Column]
    #[Groups(['bed', 'rooms_and_bed'])]
    private ?bool $hasLittleStorage = null;

    #[ORM\Column]
    #[Groups(['bed', 'rooms_and_bed'])]
    private ?bool $hasShelf = null;

    /**
     * @var Collection<int, Booking>
     */
    #[ORM\ManyToMany(targetEntity: Booking::class, mappedBy: 'beds')]
    #[Groups(['bed', 'rooms_and_bed'])]
    private Collection $bookings;

    #[ORM\Column]
    private ?bool $isOccupied = null;

    public function __construct()
    {
        $this->bookings = new ArrayCollection();
    }

    //topBed - bottomBed - singleBed

    public function getId(): ?int
    {
        return $this->id;
    }

    public function isSittingApart(): ?bool
    {
        return $this->isSittingApart;
    }

    public function setSittingApart(bool $isSittingApart): static
    {
        $this->isSittingApart = $isSittingApart;

        return $this;
    }

    public function getState(): ?string
    {
        return $this->state;
    }

    public function setState(string $state): static
    {
        $this->state = $state;

        return $this;
    }

    public function getRoom(): ?Room
    {
        return $this->room;
    }

    public function setRoom(?Room $room): static
    {
        $this->room = $room;

        return $this;
    }

    public function getNumber(): ?int
    {
        return $this->number;
    }

    public function setNumber(int $number): static
    {
        $this->number = $number;

        return $this;
    }

    public function getCleanedBy(): ?User
    {
        return $this->cleanedBy;
    }

    public function setCleanedBy(?User $cleanedBy): static
    {
        $this->cleanedBy = $cleanedBy;

        return $this;
    }

    public function getInspectedBy(): ?User
    {
        return $this->inspectedBy;
    }

    public function setInspectedBy(?User $inspectedBy): static
    {
        $this->inspectedBy = $inspectedBy;

        return $this;
    }

    public function isDoubleBed(): ?bool
    {
        return $this->isDoubleBed;
    }

    public function setDoubleBed(bool $isDoubleBed): static
    {
        $this->isDoubleBed = $isDoubleBed;

        return $this;
    }

    public function getBedShape(): ?string
    {
        return $this->bedShape;
    }


    #[Groups(['bed','rooms_and_bed'])]
    public function getCurrentBooking()
    {
        foreach ($this->bookings as $booking) {
            if ($booking->getStartDate() <= new \DateTime() && new \DateTime() <= $booking->getEndDate()) {
                return $booking;
            }
        }
        return null;
    }

    public function setBedShape(?string $bedShape): static
    {
        $this->bedShape = $bedShape;
        return $this;
    }

    public function hasLamp(): ?bool
    {
        return $this->hasLamp;
    }

    public function setHasLamp(bool $hasLamp): static
    {
        $this->hasLamp = $hasLamp;

        return $this;
    }

    public function hasLittleStorage(): ?bool
    {
        return $this->hasLittleStorage;
    }

    public function setHasLittleStorage(bool $hasLittleStorage): static
    {
        $this->hasLittleStorage = $hasLittleStorage;

        return $this;
    }

    public function hasShelf(): ?bool
    {
        return $this->hasShelf;
    }

    public function setHasShelf(bool $hasShelf): static
    {
        $this->hasShelf = $hasShelf;

        return $this;
    }

    /**
     * @return Collection<int, Booking>
     */
    public function getBookings(): Collection
    {
        return $this->bookings;
    }

    public function addBooking(Booking $booking): static
    {
        if (!$this->bookings->contains($booking)) {
            $this->bookings->add($booking);
            $booking->addBed($this);
        }

        return $this;
    }

    public function removeBooking(Booking $booking): static
    {
        if ($this->bookings->removeElement($booking)) {
            $booking->removeBed($this);
        }

        return $this;
    }

    public function isOccupied(): ?bool
    {
        return $this->isOccupied;
    }

    public function setOccupied(bool $isOccupied): static
    {
        $this->isOccupied = $isOccupied;

        return $this;
    }
}
