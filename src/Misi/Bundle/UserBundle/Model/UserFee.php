<?php

namespace Misi\Bundle\UserBundle\Model;

use Sylius\Bundle\CoreBundle\Model\UserInterface;

/**
 * This is model for fees that needs to be paid by users.
 * 
 * @author Laszlo Horvath <pentarim@gmail.com>
 */
abstract class UserFee
{
    const STATUS_ERROR = 0;
    const STATUS_INIT = 10;
    const STATUS_COMPLETED = 100;
    
    /**
     * Fee id.
     *
     * @var mixed
     */
    protected $id;

    /**
     * Amount of the fee.
     *
     * @var float
     */
    protected $amount;

    /**
     * Status of the fee.
     *
     * @var integer
     */
    protected $status;

    /**
     * User that pays the fee
     *
     * @var UserInterface
     */
    protected $user;

    /**
     * Creation time.
     *
     * @var \DateTime
     */
    protected $createdAt;

    /**
     * Last update time.
     *
     * @var \DateTime
     */
    protected $updatedAt;

    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->status = self::STATUS_INIT;
    }
    
    public function getId()
    {
        return $this->id;
    }

    /**
     * {@inheritdoc}
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * {@inheritdoc}
     */
    public function setUser(UserInterface $user)
    {
        $this->user = $user;

        return $this;
    }

    public function getAmount()
    {
        return $this->amount;
    }

    public function setAmount($amount)
    {
        $this->amount = $amount;
    }

    public function getStatus()
    {
        return $this->status;
    }

    public function setStatus($status)
    {
        $this->status = $status;
    }

    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTime $createdAt)
    {
        $this->createdAt = $createdAt;
    }

    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(\DateTime $updatedAt)
    {
        $this->updatedAt = $updatedAt;
    }

}
