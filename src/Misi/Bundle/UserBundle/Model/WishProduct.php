<?php

namespace Misi\Bundle\UserBundle\Model;

use Sylius\Bundle\CoreBundle\Model\UserInterface;
use Sylius\Bundle\CoreBundle\Model\ProductInterface;

/**
 * Wished products by users
 * 
 * @author Laszlo Horvath <pentarim@gmail.com>
 */
class WishProduct
{
    /**
     * Id
     *
     * @var integer
     */
    protected $id;
    
    /**
     * User that wishes the product
     *
     * @var UserInterface
     */
    protected $user;

    /**
     * Desired product
     *
     * @var ProductInterface
     */
    protected $product;

    /**
     * Creation time.
     *
     * @var \DateTime
     */
    protected $createdAt;

    /**
     * Constructor.
     */
    public function __construct()
    {
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

    /**
     * {@inheritdoc}
     */
    public function getProduct()
    {
        return $this->product;
    }

    /**
     * {@inheritdoc}
     */
    public function setProduct(ProductInterface $product)
    {
        $this->product = $product;

        return $this;
    }


    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTime $createdAt)
    {
        $this->createdAt = $createdAt;
    }

}
