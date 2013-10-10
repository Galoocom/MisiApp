<?php

namespace Galoo\Bundle\ProductBundle\Model;

use Sylius\Bundle\CoreBundle\Model\UserInterface;
use Sylius\Bundle\CoreBundle\Model\ProductInterface;

/**
 * Product favorite interface.
 *
 * @author Laszlo Horvath <pentarim@gmail.com>
 */
interface FavoriteProductInterface
{
    /**
     * Get id
     *
     * @return int
     */
    public function getId();
    
    /**
     * Gets product
     *
     * @return ProductInterface
     */
    public function getProduct();

    /**
     * Sets product.
     * 
     * @param ProductInterface
     */
    public function setProduct(ProductInterface $product);
    
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
    public function setUser(UserInterface $product);
    
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
