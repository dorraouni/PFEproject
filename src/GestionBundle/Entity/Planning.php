<?php

namespace GestionBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Planning
 *
 * @ORM\Table(name="planning")
 * @ORM\Entity(repositoryClass="GestionBundle\Repository\PlanningRepository")
 */
class Planning
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
    * @ORM\ManyToOne(targetEntity="UserBundle\Entity\User")
    * @ORM\JoinColumn(nullable=true)
    */
    private $user;
    /**
    * @ORM\ManyToOne(targetEntity="UserBundle\Entity\User")
    * @ORM\JoinColumn(nullable=true)
    */
    private $userDest;
    
    
     /**
     * @var \String
     *
     * @ORM\Column(name="titre", type="string")
     */
    private $titre;
    
    
      /**
     * @var \String
     *
     * @ORM\Column(name="seance", type="string")
     */
    private $seance;
     /**
     * @var \String
     *
     * @ORM\Column(name="statut", type="string")
     */
    private $statut;
    
    
    /**
     * @var \DateTime
     *
     * @ORM\Column(name="startDate", type="datetime")
     */
    private $startDate;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="endDate", type="datetime")
     */
    private $endDate;


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
     * Set titre
     *
     * @param string $titre
     *
     * @return Planning
     */
    public function setTitre($titre)
    {
        $this->titre = $titre;

        return $this;
    }

    /**
     * Get titre
     *
     * @return string
     */
    public function getTitre()
    {
        return $this->titre;
    }

   
    /**
     * Set startDate
     *
     * @param \DateTime $startDate
     *
     * @return Planning
     */
    public function setStartDate($startDate)
    {
        $this->startDate = $startDate;

        return $this;
    }

    /**
     * Get startDate
     *
     * @return \DateTime
     */
    public function getStartDate()
    {
        return $this->startDate;
    }

    /**
     * Set endDate
     *
     * @param \DateTime $endDate
     *
     * @return Planning
     */
    public function setEndDate($endDate)
    {
        $this->endDate = $endDate;

        return $this;
    }

    /**
     * Get endDate
     *
     * @return \DateTime
     */
    public function getEndDate()
    {
        return $this->endDate;
    }

    
    /**
     * Set user
     *
     * @param \UserBundle\Entity\User $user
     *
     * @return Planning
     */
    public function setUser(\UserBundle\Entity\User $user)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return \UserBundle\Entity\User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Set statut
     *
     * @param string $statut
     *
     * @return Planning
     */
    public function setStatut($statut)
    {
        $this->statut = $statut;

        return $this;
    }

    /**
     * Get statut
     *
     * @return string
     */
    public function getStatut()
    {
        return $this->statut;
    }

    /**
     * Set seance
     *
     * @param string $seance
     *
     * @return Planning
     */
    public function setSeance($seance)
    {
        $this->seance = $seance;

        return $this;
    }

    /**
     * Get seance
     *
     * @return string
     */
    public function getSeance()
    {
        return $this->seance;
    }
    
   
    /**
     * Set userDest
     *
     * @param \UserBundle\Entity\User $userDest
     *
     * @return Planning
     */
    public function setUserDest(\UserBundle\Entity\User $userDest = null)
    {
        $this->userDest = $userDest;

        return $this;
    }

    /**
     * Get userDest
     *
     * @return \UserBundle\Entity\User
     */
    public function getUserDest()
    {
        return $this->userDest;
    }
}
