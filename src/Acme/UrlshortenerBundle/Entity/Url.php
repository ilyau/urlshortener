<?php

namespace Acme\UrlshortenerBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Url
 *
 * @ORM\Table()
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks
 */
class Url
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="original_url", type="string", length=255)
     */
    private $originalUrl;

    /**
     * @var string
     *
     * @ORM\Column(name="user", type="string", length=255)
     */
    private $user;

    /**
     * @ORM\Column(type="datetime")
     */
    protected $createDatetime;

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set originalUrl
     *
     * @param string $originalUrl
     * @return Url
     */
    public function setOriginalUrl($originalUrl)
    {
        $this->originalUrl = $originalUrl;

        return $this;
    }

    /**
     * Get originalUrl
     *
     * @return string
     */
    public function getOriginalUrl()
    {
        return $this->originalUrl;
    }

    /**
     * Set user
     *
     * @param integer $user
     * @return Url
     */
    public function setUser($user)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return integer
     */
    public function getUser()
    {
        return $this->user;
    }

        /**
     * Now we tell doctrine that before we persist we call the updateTimestamps() function.
     *
     * @ORM\PrePersist
     */
    public function updateTimestamps()
    {
        if (!$this->getCreateDatetime()) {
            $this->setCreateDatetime(new \DateTime('NOW'));
        }
    }

    /**
     * Set createDatetime
     *
     * @param \DateTime $createDatetime
     */
    public function setCreateDatetime($createDatetime)
    {
        $this->createDatetime = $createDatetime;
        return $this;
    }

    /**
     * Get createDatetime
     *
     * @return \DateTime
     */
    public function getCreateDatetime()
    {
        return $this->createDatetime;
    }

}
