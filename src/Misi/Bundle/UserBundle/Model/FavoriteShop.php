<?php

namespace Misi\Bundle\UserBundle\Model;

use Sylius\Bundle\CoreBundle\Model\UserInterface;
use Galoo\Bundle\ShopBundle\Model\ShopInterface;

/**
 * Favorite shops of a user
 * 
 * @author Laszlo Horvath <pentarim@gmail.com>
 */
class FavoriteShop
{
    /**
     * Id
     *
     * @var integer
     */
    protected $id;
    
    /**
     * User
     *
     * @var UserInterface
     */
    protected $user;

    /**
     * Favorited shop
     *
     * @var ShopInterface
     */
    protected $shop;

    /**
     * Creation time.
     *
     * @var \DateTime
     */
    protected $createdAt;

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
    public function getShop()
    {
        return $this->shop;
    }

    /**
     * {@inheritdoc}
     */
    public function setShop(ShopInterface $shop)
    {
        $this->shop = $shop;

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
