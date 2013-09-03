<?php

namespace Sylius\Bundle\CoreBundle\Model;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Sylius\Bundle\CoreBundle\Model\UserInterface;
use Galoo\Bundle\ShopBundle\Model\ShopInterface;
use Galoo\Bundle\ShopBundle\Model\Shop as BaseShop;

/**
 * Shop model.
 *
 * @author Laszlo Horvath <pentarim@gmail.com>
 */
class Shop extends BaseShop implements ShopInterface
{
    /**
     * The owner of the shop
     *
     * @var UserInterface
     */
    protected $user;
     
    /**
     * The owner of the shop
     *
     * @var OrderInterface
     */
    protected $orders;
     
    public function __construct() 
    {
        parent::__construct();

        $this->orders   = new ArrayCollection();
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
    public function getOrders()
    {
        return $this->orders;
    }
}
