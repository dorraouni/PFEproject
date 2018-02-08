<?php

namespace GestionBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * PushNotif
 *
 * @ORM\Table(name="push_notif")
 * @ORM\Entity(repositoryClass="GestionBundle\Repository\PushNotifRepository")
 */
class PushNotif
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
     * @var string
     *
     * @ORM\Column(name="deviceID", type="string", length=255)
     */
    private $deviceID;

    /**
     * @var string
     *
     * @ORM\Column(name="token", type="string", length=255)
     */
    private $token;


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
     * Set deviceID
     *
     * @param string $deviceID
     *
     * @return PushNotif
     */
    public function setDeviceID($deviceID)
    {
        $this->deviceID = $deviceID;

        return $this;
    }

    /**
     * Get deviceID
     *
     * @return string
     */
    public function getDeviceID()
    {
        return $this->deviceID;
    }

    /**
     * Set token
     *
     * @param string $token
     *
     * @return PushNotif
     */
    public function setToken($token)
    {
        $this->token = $token;

        return $this;
    }

    /**
     * Get token
     *
     * @return string
     */
    public function getToken()
    {
        return $this->token;
    }

    /**
     * Set user
     *
     * @param \UserBundle\Entity\User $user
     *
     * @return PushNotif
     */
    public function setUser(\UserBundle\Entity\User $user = null)
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
}
