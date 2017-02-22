<?php

namespace BlogBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use DateTime;
/**
 * Comment
 *
 * @ORM\Table(name="comment")
 * @ORM\Entity(repositoryClass="BlogBundle\Repository\CommentRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class Comment
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
     * @var string
     *
     * @ORM\Column(name="username", type="string", length=255)
     */
    private $username;

    /**
     * @var string
     *
     * @ORM\Column(name="email", type="string", length=255)
     */
    private $email;

    /**
     * @var string
     *
     * @ORM\Column(name="content", type="text")
     */
    private $content;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="createdAt", type="datetime")
     */
    private $createdAt;

    /**
     *
     * @ORM\ManyToOne(targetEntity="Post", inversedBy="comments", cascade={"persist"})
     */
    private $post;

    /**
     *
     * @ORM\OneToMany(targetEntity="CommentResponse", mappedBy="comment", cascade={"persist"})
     */
    private $commentResponse;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->createdAt = new DateTime();
    }

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
     * Set username
     *
     * @param string $username
     *
     * @return Comment
     */
    public function setUsername($username)
    {
        $this->username = $username;

        return $this;
    }

    /**
     * Get username
     *
     * @return string
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * Set email
     *
     * @param string $email
     *
     * @return Comment
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email
     *
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set content
     *
     * @param string $content
     *
     * @return Comment
     */
    public function setContent($content)
    {
        $this->content = $content;

        return $this;
    }

    /**
     * Get content
     *
     * @return string
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     *
     * @return Comment
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * @ORM\PrePersist
     */
    public function createdAt()
    {
        $this->setCreatedAt(new Datetime());
    }

    /**
     * Get createdAt
     *
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * Set post
     *
     * @param string $post
     *
     * @return Comment
     */
    public function setPost($post)
    {
        $this->post = $post;

        return $this;
    }

    /**
     * Get post
     *
     * @return string
     */
    public function getPost()
    {
        return $this->post;
    }

    /**
     * Set commentResponse
     *
     * @param \BlogBundle\Entity\CommentResponse $commentResponse
     *
     * @return Comment
     */
    public function setCommentResponse(\BlogBundle\Entity\CommentResponse $commentResponse = null)
    {
        $this->commentResponse = $commentResponse;

        return $this;
    }

    /**
     * Get commentResponse
     *
     * @return \BlogBundle\Entity\CommentResponse
     */
    public function getCommentResponse()
    {
        return $this->commentResponse;
    }

    /**
     * Add commentResponse
     *
     * @param \BlogBundle\Entity\CommentResponse $commentResponse
     *
     * @return Comment
     */
    public function addCommentResponse(\BlogBundle\Entity\CommentResponse $commentResponse)
    {
        $this->commentResponse[] = $commentResponse;

        return $this;
    }

    /**
     * Remove commentResponse
     *
     * @param \BlogBundle\Entity\CommentResponse $commentResponse
     */
    public function removeCommentResponse(\BlogBundle\Entity\CommentResponse $commentResponse)
    {
        $this->commentResponse->removeElement($commentResponse);
    }
}
