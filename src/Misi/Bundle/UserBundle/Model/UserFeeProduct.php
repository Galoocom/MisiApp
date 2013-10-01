<?php

namespace Misi\Bundle\UserBundle\Model;

use Sylius\Bundle\ProductBundle\Model\ProductInterface;

/**
 * This is model for fees that needs to be paid by users.
 * 
 * @author Laszlo Horvath <pentarim@gmail.com>
 */
class UserFeeProduct extends UserFee
{
    /**
     * @var ProductInterface 
     */
    protected $product;
    
    public function setProduct(ProductInterface $product)
    {
        $this->product = $product;
    }
    
    public function getProduct()
    {
        return $this->product;
    }
}