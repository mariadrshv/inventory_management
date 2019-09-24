<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\RequestsRepository")
 */
class Requests
{
    const TYPES = [
        'Replace' => 'Replace',
        'Service' => 'Service'
    ];

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $notes;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Property", inversedBy="requests")
     * @ORM\JoinColumn(nullable=false)
     */
    private $property;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Room", inversedBy="requests")
     * @ORM\JoinColumn(nullable=false)
     */
    private $room;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Item", inversedBy="requests")
     * @ORM\JoinColumn(nullable=false)
     */
    private $item;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $visit_date_and_time;

    /**
     * @ORM\Column(type="string", columnDefinition="enum('Replace','Service')", length=255)
     * @Assert\Choice(choices=Requests::TYPES, message="Choose a valid type.")
     */
    private $type_of_request;

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return string|null
     */
    public function getStatus(): ?string
    {
        return $this->status;
    }

    /**
     * @param string|null $status
     * @return Requests
     */
    public function setStatus(?string $status): self
    {
        $this->status = $status;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getPropertyAddress(): ?string
    {
        return $this->propertyAddress;
    }

    /**
     * @param string $propertyAddress
     * @return Requests
     */
    public function setPropertyAddress(string $propertyAddress): self
    {
        $this->propertyAddress = $propertyAddress;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getNotes(): ?string
    {
        return $this->notes;
    }

    /**
     * @param string $notes
     * @return Requests
     */
    public function setNotes(string $notes): self
    {
        $this->notes = $notes;

        return $this;
    }

    /**
     * @return user|null
     */
    public function getAssignee(): ?user
    {
        return $this->assignee;
    }

    /**
     * @param user|null $assignee
     * @return Requests
     */
    public function setAssignee(?user $assignee): self
    {
        $this->assignee = $assignee;

        return $this;
    }

    /**
     * @return \DateTimeInterface|null
     */
    public function getCreationDate(): ?\DateTimeInterface
    {
        return $this->creation_date;
    }

    /**
     * @param \DateTimeInterface $creation_date
     * @return Requests
     */
    public function setCreationDate(\DateTimeInterface $creation_date): self
    {
        $this->creation_date = $creation_date;

        return $this;
    }

    /**
     * @return \DateTimeInterface|null
     */
    public function getUpdateDate(): ?\DateTimeInterface
    {
        return $this->update_date;
    }

    /**
     * @param \DateTimeInterface|null $update_date
     * @return Requests
     */
    public function setUpdateDate(?\DateTimeInterface $update_date): self
    {
        $this->update_date = $update_date;

        return $this;
    }

    /**
     * @return Property|null
     */
    public function getProperty(): ?Property
    {
        return $this->property;
    }

    /**
     * @param Property|null $property
     * @return Requests
     */
    public function setProperty(?Property $property): self
    {
        $this->property = $property;

        return $this;
    }

    /**
     * @return Room|null
     */
    public function getRoom(): ?Room
    {
        return $this->room;
    }

    /**
     * @param Room|null $room
     * @return Requests
     */
    public function setRoom(?Room $room): self
    {
        $this->room = $room;

        return $this;
    }

    /**
     * @return Item|null
     */
    public function getItem(): ?Item
    {
        return $this->item;
    }

    /**
     * @param Item|null $item
     * @return Requests
     */
    public function setItem(?Item $item): self
    {
        $this->item = $item;

        return $this;
    }

    /**
     * @return \DateTimeInterface|null
     */
    public function getVisitDateAndTime(): ?\DateTimeInterface
    {
        return $this->visit_date_and_time;
    }

    /**
     * @param \DateTimeInterface|null $visit_date_and_time
     * @return Requests
     */
    public function setVisitDateAndTime(?\DateTimeInterface $visit_date_and_time): self
    {
        $this->visit_date_and_time = $visit_date_and_time;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getTypeOfRequest(): ?string
    {
        return $this->type_of_request;
    }

    /**
     * @param string $type_of_request
     * @return Requests
     */
    public function setTypeOfRequest(string $type_of_request): self
    {
        $this->type_of_request = $type_of_request;

        return $this;
    }
}
