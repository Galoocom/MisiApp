<?php

namespace Galoo\Bundle\ShopBundle\Model;

use Sylius\Bundle\CoreBundle\Model\UserInterface;
use Galoo\Bundle\ShopBundle\Model\ShopInterface;

/**
 * Shop favorite interface.
 *
 * @author Laszlo Horvath <pentarim@gmail.com>
 */
interface FavoriteShopInterface
{
    /**
     * Get id
     *
     * @return int
     */
    public function getId();

    /**
     * Gets shop
     *
     * @return ShopInterface
     */
    public function getShop();

    /**
     * Sets shop.
     * 
     * @param ShopInterface
     */
    public function setShop(ShopInterface $shop);
    
    /**
     * Gets user
     *
     * @return UserInterface
     */
    public function getUser();

    /**
     * Sets user.
     * 
     * @param UserInterface
     */
    public function setUser(UserInterface $shop);
    
    /**
     * Get creation time.
     *
     * @return \DateTime
     */
    public function getCreatedAt();

    /**
     * Get creation time.
     *
     * @param \DateTime $createdAt
     */
    public function setCreatedAt(\DateTime $createdAt);
    

}
