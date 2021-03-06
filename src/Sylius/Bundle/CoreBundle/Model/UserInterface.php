<?php

/*
* This file is part of the Sylius package.
*
* (c) Paweł Jędrzejewski
*
* For the full copyright and license information, please view the LICENSE
* file that was distributed with this source code.
*/

namespace Sylius\Bundle\CoreBundle\Model;

use Doctrine\Common\Collections\ArrayCollection;
use FOS\UserBundle\Model\UserInterface as BaseUserInterface;
use Sylius\Bundle\AddressingBundle\Model\AddressInterface;
use Sylius\Bundle\ResourceBundle\Model\TimestampableInterface;

/**
 * User interface.
 *
 * @author Paweł Jędrzjewski <pjedrzejewski@diweb.pl>
 */
interface UserInterface extends BaseUserInterface, TimestampableInterface
{
    public function getFirstName();

    /**
     * Set firstname.
     *
     * @param string $firstName
     */
    public function setFirstName($firstName);

    public function getLastName();
    public function setLastName($lastName);

    public function getCurrency();
    public function setCurrency($currency);

    /**
     * Get orders.
     *
     * @return ArrayCollection
     */
    public function getOrders();

    /**
     * Get billing address.
     *
     * @return AddressInterface
     */
    public function getBillingAddress();

    /**
     * Set billing address.
     *
     * @param AddressInterface $billingAddress
     */
    public function setBillingAddress(AddressInterface $billingAddress = null);

    /**
     * Get shipping address.
     *
     * @return AddressInterface
     */
    public function getShippingAddress();

    /**
     * Set shipping address.
     *
     * @param AddressInterface $shippingAddress
     */
    public function setShippingAddress(AddressInterface $shippingAddress = null);

    /**
     * Get addresses.
     *
     * @return ArrayCollection
     */
    public function getAddresses();

    /**
     * Add address.
     *
     * @param AddressInterface $address
     */
    public function addAddress(AddressInterface $address);

    /**
     * Remove address.
     *
     * @param AddressInterface $addresses
     */
    public function removeAddress(AddressInterface $address);

    /**
     * Has address?
     *
     * @param AddressInterface $addresses
     *
     * @return Boolean
     */
    public function hasAddress(AddressInterface $address);
    
    /**
     * Get shops.
     *
     * @return ArrayCollection
     */
    public function getShops();

    /**
     * Get favorited shops.
     *
     * @return ArrayCollection
     */
    public function getFavoriteShops();

    /**
     * Get wished products.
     *
     * @return ArrayCollection
     */
    public function getWishlist();

    
}
