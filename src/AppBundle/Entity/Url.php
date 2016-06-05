<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Url
 *
 * @ORM\Table(name="url")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\UrlRepository")
 */
class Url
{
    /**
     * Times the URL can be redirected
     */
    const MAX_REDIRECTS = 5;

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
     * @ORM\Column(name="name", type="string", length=255, unique=true)
     */
    private $name;

    /**
     * @var int
     *
     * @ORM\Column(name="batch", type="integer")
     */
    private $batch;

    /**
     * @var RequestStatus
     *
     * @ORM\Column(name="status", type="integer")
     */
    private $status;

    /**
     * Url constructor.
     * @param string $name
     * @param int $batch
     * @param $statusCode
     */
    public function __construct($name, $batch, $statusCode)
    {
        $this->name = $name;
        $this->batch = $batch;
        $this->status = $statusCode;
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
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Get batch
     *
     * @return int
     */
    public function getBatch()
    {
        return $this->batch;
    }

    /**
     * @return int
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @param int $status
     */
    public function setStatus($status)
    {
        $this->status = $status;
    }
}

