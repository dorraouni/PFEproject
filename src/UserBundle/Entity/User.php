<?php

namespace UserBundle\Entity;

use FOS\UserBundle\Model\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;


/**
 * @ORM\Entity
 * @ORM\Table(name="fos_user")
 */
class User extends BaseUser
{
    
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;
    
    
    /**
    * @ORM\ManyToOne(targetEntity="UserBundle\Entity\User")
    * @ORM\JoinColumn(nullable=true)
    */
    protected $moniteur;
    
     /**
     * @ORM\Column(type="string")
   
     */
    protected $nom;
    
    /**
     * @ORM\Column(type="string")
   
     */
    protected $prenom;
    
     /**
     * @ORM\Column(type="string")
   
     */
    protected $adresse;
    
    /**
     * @ORM\Column(type="date")
   
     */
    protected $date_naissance;
    
    
    /**
     * @ORM\Column(name="telephone", type="string")
   
     */
    protected $telephone;
    
    /**
     * @ORM\Column(type="string")
   
     */
    protected $photo;
    
    
    public function __construct()
    {
        parent::__construct();
        // your own logic
        
        
    }

    /**
     * Set nom
     *
     * @param string $nom
     *
     * @return User
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
     * Set telephone
     *
     * @param string $telephone
     *
     * @return User
     */
    public function setTelephone($telephone)
    {
        $this->telephone = $telephone;

        return $this;
    }

    /**
     * Get telephone
     *
     * @return string
     */
    public function getTelephone()
    {
        return $this->telephone;
    }

    /**
     * Set prenom
     *
     * @param string $prenom
     *
     * @return User
     */
    public function setPrenom($prenom)
    {
        $this->prenom = $prenom;

        return $this;
    }

    /**
     * Get prenom
     *
     * @return string
     */
    public function getPrenom()
    {
        return $this->prenom;
    }

    /**
     * Set dateNaissance
     *
     * @param \DateTime $dateNaissance
     *
     * @return User
     */
    public function setDateNaissance($dateNaissance)
    {
        $this->date_naissance = $dateNaissance;

        return $this;
    }

    /**
     * Get dateNaissance
     *
     * @return \DateTime
     */
    public function getDateNaissance()
    {
        return $this->date_naissance;
    }



    /**
     * Set moniteur
     *
     * @param \UserBundle\Entity\User $moniteur
     *
     * @return User
     */
    public function setMoniteur(\UserBundle\Entity\User $moniteur = null)
    {
        $this->moniteur = $moniteur;

        return $this;
    }

    /**
     * Get moniteur
     *
     * @return \UserBundle\Entity\User
     */
    public function getMoniteur()
    {
        return $this->moniteur;
    }

    /**
     * Set photo
     *
     * @param string $photo
     *
     * @return User
     */
    public function setPhoto($photo)
    {
        $this->photo = $photo;

        return $this;
    }

    /**
     * Get photo
     *
     * @return string
     */
    public function getPhoto()
    {
        return $this->photo;
    }

    /**
     * Set adresse
     *
     * @param string $adresse
     *
     * @return User
     */
    public function setAdresse($adresse)
    {
        $this->adresse = $adresse;

        return $this;
    }

    /**
     * Get adresse
     *
     * @return string
     */
    public function getAdresse()
    {
        return $this->adresse;
    }
}
