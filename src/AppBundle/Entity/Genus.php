<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="AppBundle\Repository\GenusRepository")
 * @ORM\Table(name="genus")
 */
class Genus {

    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string")
     * @Assert\NotBlank()
     */
    private $name;

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\SubFamily")
     * @ORM\JoinColumn(nullable=false)
     */
    private $subFamily;

    /**
     * @ORM\Column(type="integer")
     * @Assert\NotBlank()
     * @Assert\Range(min=0, minMessage="Negative species! Come on...")
     */
    private $specCount;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private $funFact;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $isPublished = true;

    /**
     * @ORM\OneToMany(targetEntity="GenusNote", mappedBy="genus")
     * @ORM\OrderBy({"createdAt" = "DESC"})
     */
    private $notes;

    /**
     * @ORM\Column(type="date")
     * @Assert\NotBlank()
     */
    private $firstDiscoveredAt;

    public function __construct() {
        $this->notes = new ArrayCollection();
    }

    public function getId() {
        return $this->id;
    }

    /**
     * @return ArrayCollection|GenusNote[]
     */
    public function getNotes() {
        return $this->notes;
    }

    /**
     * @return SubFamily
     */
    public function getSubFamily() {
        return $this->subFamily;
    }

    public function setSubFamily(SubFamily $subFamily) {
        $this->subFamily = $subFamily;
    }

    public function getSpecCount() {
        return $this->specCount;
    }

    public function setSpecCount($specCount) {
        $this->specCount = $specCount;
    }

    public function getFunFact() {
        return $this->funFact;
    }

    public function setFunFact($funFact) {
        $this->funFact = $funFact;
    }

    public function getName() {
        return $this->name;
    }

    public function setName($name) {
        $this->name = $name;
    }

    public function getisPublished() {
        return $this->isPublished;
    }

    public function setIsPublished($isPublished) {
        $this->isPublished = $isPublished;
    }

    public function getFirstDiscoveredAt() {
        return $this->firstDiscoveredAt;
    }

    public function setFirstDiscoveredAt($firstDiscoveredAt) {
        $this->firstDiscoveredAt = $firstDiscoveredAt;
    }

    public function getUpdatedAt() {
        return new \DateTime('-' . rand(0, 100) . ' days');
    }
}