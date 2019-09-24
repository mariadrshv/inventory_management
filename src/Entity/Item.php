<?php

namespace App\Entity;

use App\Interfaces\EntityWithWarrantyInterface;
use App\Interfaces\EntityWithMaintenanceInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * class Item
 * @property  string
 * @ORM\Entity(repositoryClass="App\Repository\ItemRepository")
 * @ORM\Table(name="item")
 */
class Item implements EntityWithWarrantyInterface, EntityWithMaintenanceInterface
{
    const TYPES = [
        'Appliance' => 'Appliance',
        'Electronic Devices' => 'Electronic Devices',
        'Household' => 'Household',
        'Auto' => 'Auto'];

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Room", inversedBy="items")
     */
    private $room;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Warranties", mappedBy="item")
     */
    private $warranties;

    /**
     * @ORM\Column(type="string", columnDefinition="enum('Appliance','Electronic Devices','Household', 'Auto')", length=255, nullable=true)
     * @Assert\Choice(choices=Item::TYPES, message="Choose a valid type.")
     */
    private $type;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $width;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $height;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $depth;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $serial_number;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $model;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $manufacturer;

    /**
     * @ORM\Column(type="date", nullable=true)
     */
    private $purchase_date;

    /**
     * @ORM\Column(type="decimal", precision=15, scale=2, nullable=true)
     */
    private $purchase_price;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $note;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $make;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $year;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $VIN;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $exterior_color;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $interior_color;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Maintenance", mappedBy="item")
     */
    private $maintenances;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Photos", inversedBy="items")
     */
    private $photos;

    /**
     * Item constructor.
     */
    public function __construct()
    {
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
     * @return string|null
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * @param string $name
     * @return Item
     */
    public function setName(string $name): self
    {
        $this->name = $name;

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
     * @return Item
     */
    public function setRoom(?Room $room): self
    {
        $this->room = $room;

        return $this;
    }

    /**
     * @return mixed
     */
    public function __toString(){
        return $this->name;
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
     * @return Item
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
    public function getType(): ?string
    {
        return $this->type;
    }

    /**
     * @param string|null $type
     * @return Item
     */
    public function setType(?string $type): self
    {
        $this->type = $type;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getWidth(): ?string
    {
        return $this->width;
    }

    /**
     * @param string|null $width
     * @return Item
     */
    public function setWidth(?string $width): self
    {
        $this->width = $width;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getHeight(): ?string
    {
        return $this->height;
    }

    /**
     * @param string|null $height
     * @return Item
     */
    public function setHeight(?string $height): self
    {
        $this->height = $height;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getDepth(): ?string
    {
        return $this->depth;
    }

    /**
     * @param string|null $depth
     * @return Item
     */
    public function setDepth(?string $depth): self
    {
        $this->depth = $depth;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getSerialNumber(): ?string
    {
        return $this->serial_number;
    }

    /**
     * @param string|null $serial_number
     * @return Item
     */
    public function setSerialNumber(?string $serial_number): self
    {
        $this->serial_number = $serial_number;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getModel(): ?string
    {
        return $this->model;
    }

    /**
     * @param string $model
     * @return Item
     */
    public function setModel(string $model): self
    {
        $this->model = $model;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getString(): ?string
    {
        return $this->string;
    }

    /**
     * @param string|null $string
     * @return Item
     */
    public function setString(?string $string): self
    {
        $this->string = $string;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getManufacturer(): ?string
    {
        return $this->manufacturer;
    }

    /**
     * @param string|null $manufacturer
     * @return Item
     */
    public function setManufacturer(?string $manufacturer): self
    {
        $this->manufacturer = $manufacturer;
        return $this;
    }

    /**
     * @return \DateTimeInterface|null
     */
    public function getPurchaseDate(): ?\DateTimeInterface
    {
        return $this->purchase_date;
    }

    /**
     * @param \DateTimeInterface|null $purchase_date
     * @return Item
     */
    public function setPurchaseDate(?\DateTimeInterface $purchase_date): self
    {
        $this->purchase_date = $purchase_date;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getPurchasePrice(): ?string
    {
        return $this->purchase_price;
    }

    /**
     * @param string|null $purchase_price
     * @return Item
     */
    public function setPurchasePrice(?string $purchase_price): self
    {
        $this->purchase_price = $purchase_price;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getNote(): ?string
    {
        return $this->note;
    }

    /**
     * @param string|null $note
     * @return Item
     */
    public function setNote(?string $note): self
    {
        $this->note = $note;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getMake(): ?string
    {
        return $this->make;
    }

    /**
     * @param string|null $make
     * @return Item
     */
    public function setMake(?string $make): self
    {
        $this->make = $make;
        return $this;
    }

    /**
     * @return int|null
     */
    public function getYear(): ?int
    {
        return $this->year;
    }

    /**
     * @param int|null $year
     * @return Item
     */
    public function setYear(?int $year): self
    {
        $this->year = $year;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getVIN(): ?string
    {
        return $this->VIN;
    }

    /**
     * @param string|null $VIN
     * @return Item
     */
    public function setVIN(?string $VIN): self
    {
        $this->VIN = $VIN;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getExteriorColor(): ?string
    {
        return $this->exterior_color;
    }

    /**
     * @param string|null $exterior_color
     * @return Item
     */
    public function setExteriorColor(?string $exterior_color): self
    {
        $this->exterior_color = $exterior_color;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getInteriorColor(): ?string
    {
        return $this->interior_color;
    }

    /**
     * @param string|null $interior_color
     * @return Item
     */
    public function setInteriorColor(?string $interior_color): self
    {
        $this->interior_color = $interior_color;
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
     * @return Item
     */
    public function addMaintenance(Maintenance $maintenance): self
    {
        if (!$this->maintenances->contains($maintenance)) {
            $this->maintenances[] = $maintenance;
            $maintenance->setItem($this);
        }
        return $this;
    }

    /**
     * @param Maintenance $maintenance
     * @return Item
     */
    public function removeMaintenance(Maintenance $maintenance): self
    {
        if ($this->maintenances->contains($maintenance)) {
            $this->maintenances->removeElement($maintenance);
            // set the owning side to null (unless already changed)
            if ($maintenance->getItem() === $this) {
                $maintenance->setItem(null);
            }
        }
        return $this;
    }

    /**
     * @return user|null
     */
    public function getUserId(): ?user
    {
        return $this->getRoom()->getProperty()->getUserId();

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
     * @return Item
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
     * @return Item
     */
    public function removePhoto(Photos $photo): self
    {
        if ($this->photos->contains($photo)) {
            $this->photos->removeElement($photo);
        }

        return $this;
    }
}
