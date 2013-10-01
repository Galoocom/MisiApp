<?php

namespace Misi\Bundle\UserBundle\Fee;

use Sylius\Bundle\CoreBundle\Model\ProductInterface;

interface ProductFeeCalculatorInterface
{
    public function calculateFee(ProductInterface $product);
}

