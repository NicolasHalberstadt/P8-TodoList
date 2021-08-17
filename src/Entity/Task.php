<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity
 * @ORM\Table
 */
class Task
{
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;
    
    /**
     * @ORM\Column(type="datetime")
     */
    private $createdAt;
    
    /**
     * @ORM\Column(type="string")
     * @Assert\NotBlank(message="Vous devez saisir un titre.")
     */
    private $title;
    
    /**
     * @ORM\Column(type="text")
     * @Assert\NotBlank(message="Vous devez saisir du contenu.")
     */
    private $content;
    
    /**
     * @ORM\Column(type="boolean")
     */
    private $isDone;
    
    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\User", inversedBy="tasks")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     */
    private $user;
    
    
    public function __construct()
    {
        $this->createdAt = new \Datetime();
        $this->isDone = false;
    }
    
    public function getId()
    {
        return $this->id;
    }
    
    public function getCreatedAt()
    {
        return $this->createdAt;
    }
    
    public function getTitle()
    {
        return $this->title;
    }
    
    public function setTitle($title)
    {
        $this->title = $title;
    }
    
    public function getContent()
    {
        return $this->content;
    }
    
    public function setContent($content)
    {
        $this->content = $content;
    }
    
    public function isDone()
    {
        return $this->isDone;
    }
    
    public function toggle($flag)
    {
        $this->isDone = $flag;
    }
    
    /**
     * @return mixed
     */
    public function getUser()
    {
        return $this->user;
    }
    
    /**
     * @param mixed $user
     */
    public function setUser($user)
    {
        $this->user = $user;
    }
}