<?php

namespace GestionBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * FileCours
 *
 * @ORM\Table(name="file_cours")
 * @ORM\Entity(repositoryClass="GestionBundle\Repository\FileCoursRepository")
 */
class FileCours
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
    * @ORM\ManyToOne(targetEntity="GestionBundle\Entity\Cours")
    * @ORM\JoinColumn(nullable=true)
    */
   private $cours;
    /**
     * @var string
     *
     * @ORM\Column(name="nom", type="string", length=255)
     */
    private $nom;


    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set nom
     *
     * @param string $nom
     *
     * @return FileCours
     */
    public function setNom($nom)
    {
        $this->nom = $nom;

        return $this;
    }

    /**
     * Get nom
     *
     * @return string
     */
    public function getNom()
    {
        return $this->nom;
    }

    /**
     * Set cours
     *
     * @param \GestionBundle\Entity\Cours $cours
     *
     * @return FileCours
     */
    public function setCours(\GestionBundle\Entity\Cours $cours = null)
    {
        $this->cours = $cours;

        return $this;
    }

    /**
     * Get cours
     *
     * @return \GestionBundle\Entity\Cours
     */
    public function getCours()
    {
        return $this->cours;
    }
}
