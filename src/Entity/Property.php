<?php

namespace App\Entity;

use App\Interfaces\EntityWithMaintenanceInterface;
use App\Interfaces\EntityWithWarrantyInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class Property
 * @ORM\Entity(repositoryClass="App\Repository\PropertyRepository")
 * @ORM\Table(name="property")
 */
class Property implements EntityWithWarrantyInterface, EntityWithMaintenanceInterface
{
    const STATES = [
        'AL' => 'AL',
        'AK' => 'AK',
        'AZ' => 'AZ'];

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="properties")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user_id;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Room", mappedBy="property")
     */
    private $rooms;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $name;


    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Warranties", mappedBy="property")
     */
    private $warranties;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $phone_number;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $line1;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $line2;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $city;

    /**
     * @ORM\Column(type="string", length=255, columnDefinition="enum('AL','AK','AZ')", length=255, nullable=true)
     * @Assert\Choice(choices=Property::STATES, message="Choose a valid state.")
     */
    private $state;

    /**
     * @Assert\Regex("/[0-9]/")
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $zip;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Maintenance", mappedBy="property")
     */
    private $maintenances;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Photos", inversedBy="properties")
     */
    private $photos;

    /**
     * Property constructor.
     */
    public function __construct()
    {
        $this->rooms = new ArrayCollection();
        $this->warranties = new ArrayCollection();
        $this->maintenances = new ArrayCollection();
        $this->photos = new ArrayCollection();
    }

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return User|null
     */
    public function getUserId(): ?User
    {
        return $this->user_id;
    }

    /**
     * @param User|null $user_id
     * @return Property
     */
    public function setUserId(?User $user_id): self
    {
        $this->user_id = $user_id;

        return $this;
    }

    /**
     * @return Collection|Room[]
     */
    public function getRooms(): Collection
    {
        return $this->rooms;
    }

    /**
     * @param Room $room
     * @return Property
     */
    public function addRoom(Room $room): self
    {
        if (!$this->rooms->contains($room)) {
            $this->rooms[] = $room;
            $room->setProperty($this);
        }

        return $this;
    }

    /**
     * @param Room $room
     * @return Property
     */
    public function removeRoom(Room $room): self
    {
        if ($this->rooms->contains($room)) {
            $this->rooms->removeElement($room);
            // set the owning side to null (unless already changed)
            if ($room->getProperty() === $this) {
                $room->setProperty(null);
            }
        }

        return $this;
    }

    /**
     * @return mixed
     */
    public function __toString(){
        return $this->name;
    }

    /**
     * @return string|null
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * @param string|null $name
     * @return Property
     */
    public function setName(?string $name): self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return Collection|Warranties[]
     */
    public function getWarranties(): Collection
    {
        return $this->warranties;
    }

    /**
     * @param Warranties $warranty
     * @return Property
     */
    public function addWarranty(Warranties $warranty): self
    {
        if (!$this->warranties->contains($warranty)) {
            $this->warranties[] = $warranty;
            $warranty->setProperty($this);
        }

        return $this;
    }

    /**
     * @param Warranties $warranty
     * @return Property
     */
    public function removeWarranty(Warranties $warranty): self
    {
        if ($this->warranties->contains($warranty)) {
            $this->warranties->removeElement($warranty);
            // set the owning side to null (unless already changed)
            if ($warranty->getProperty() === $this) {
                $warranty->setProperty(null);
            }
        }

        return $this;
    }

    /**
     * @return string|null
     */
    public function getPhoneNumber(): ?string
    {
        return $this->phone_number;
    }

    /**
     * @param string|null $phone_number
     * @return Property
     */
    public function setPhoneNumber(?string $phone_number): self
    {
        $this->phone_number = $phone_number;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getLine1(): ?string
    {
        return $this->line1;
    }

    /**
     * @param string|null $line1
     * @return Property
     */
    public function setLine1(?string $line1): self
    {
        $this->line1 = $line1;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getLine2(): ?string
    {
        return $this->line2;
    }

    /**
     * @param string|null $line2
     * @return Property
     */
    public function setLine2(?string $line2): self
    {
        $this->line2 = $line2;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getCity(): ?string
    {
        return $this->city;
    }

    /**
     * @param string|null $city
     * @return Property
     */
    public function setCity(?string $city): self
    {
        $this->city = $city;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getState(): ?string
    {
        return $this->state;
    }

    /**
     * @param string|null $state
     * @return Property
     */
    public function setState(?string $state): self
    {
        $this->state = $state;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getZip(): ?string
    {
        return $this->zip;
    }

    /**
     * @param string|null $zip
     * @return Property
     */
    public function setZip(?string $zip): self
    {
        $this->zip = $zip;

        return $this;
    }

    /**
     * @return Collection|Maintenance[]
     */
    public function getMaintenances(): Collection
    {
        return $this->maintenances;
    }

    /**
     * @param Maintenance $maintenance
     * @return Property
     */
    public function addMaintenance(Maintenance $maintenance): self
    {
        if (!$this->maintenances->contains($maintenance)) {
            $this->maintenances[] = $maintenance;
            $maintenance->setProperty($this);
        }

        return $this;
    }

    /**
     * @param Maintenance $maintenance
     * @return Property
     */
    public function removeMaintenance(Maintenance $maintenance): self
    {
        if ($this->maintenances->contains($maintenance)) {
            $this->maintenances->removeElement($maintenance);
            // set the owning side to null (unless already changed)
            if ($maintenance->getProperty() === $this) {
                $maintenance->setProperty(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Photos[]
     */
    public function getPhotos(): Collection
    {
        return $this->photos;
    }

    /**
     * @param Photos $photo
     * @return Property
     */
    public function addPhoto(Photos $photo): self
    {
        if (!$this->photos->contains($photo)) {
            $this->photos[] = $photo;
        }

        return $this;
    }

    /**
     * @param Photos $photo
     * @return Property
     */
    public function removePhoto(Photos $photo): self
    {
        if ($this->photos->contains($photo)) {
            $this->photos->removeElement($photo);
        }

        return $this;
    }
}
