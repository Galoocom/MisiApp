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

use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use FOS\UserBundle\Model\User as BaseUser;
use Sylius\Bundle\AddressingBundle\Model\AddressInterface;
use FOS\MessageBundle\Model\ParticipantInterface;

/**
 * User model.
 *
 * @author Paweł Jędrzjewski <pjedrzejewski@diweb.pl>
 */
class User extends BaseUser implements UserInterface, ParticipantInterface
{
    protected $firstName;
    protected $lastName;
    protected $createdAt;
    protected $updatedAt;
    protected $currency;
    protected $orders;
    protected $shops;
    protected $billingAddress;
    protected $shippingAddress;
    protected $addresses;
    protected $facebookId;
    protected $twitterId;    
    protected $wishlist;    
    protected $favoriteShops;    
    
    /**
     * @var boolean
     */
    protected $legacyPassword;


    public function __construct()
    {
        $this->createdAt      = new DateTime();
        $this->orders         = new ArrayCollection();
        $this->shops          = new ArrayCollection();
        $this->addresses      = new ArrayCollection();
        $this->legacyPassword = false;
        $this->wishlist       = new ArrayCollection();
        $this->favoriteShops  = new ArrayCollection();

        parent::__construct();
    }

    public function getCurrency()
    {
        return $this->currency;
    }

    public function setCurrency($currency)
    {
        $this->currency = $currency;
    }

    /**
     * Get orders
     *
     * @return ArrayCollection
     */
    public function getOrders()
    {
        return $this->orders;
    }

    /**
     * Get shops
     *
     * @return ArrayCollection
     */
    public function getShops()
    {
        return $this->shops;
    }

    /**
     * Get shops
     *
     * @return ArrayCollection
     */
    public function getFavoriteShops()
    {
        return $this->favoriteShops;
    }

    /**
     * Get wishlist
     *
     * @return ArrayCollection
     */
    public function getWishlist()
    {
        return $this->wishlist;
    }

    /**
     * Set billingAddress
     *
     * @param  AddressInterface $billingAddress
     * @return User
     */
    public function setBillingAddress(AddressInterface $billingAddress = null)
    {
        $this->billingAddress = $billingAddress;

        if (null !== $billingAddress && !$this->hasAddress($billingAddress)) {
            $this->addAddress($billingAddress);
        }

        return $this;
    }

    /**
     * Get billingAddress
     *
     * @return AddressInterface
     */
    public function getBillingAddress()
    {
        return $this->billingAddress;
    }

    /**
     * Set shippingAddress
     *
     * @param  AddressInterface $shippingAddress
     * @return User
     */
    public function setShippingAddress(AddressInterface $shippingAddress = null)
    {
        $this->shippingAddress = $shippingAddress;

        if (null !== $shippingAddress && !$this->hasAddress($shippingAddress)) {
            $this->addAddress($shippingAddress);
        }

        return $this;
    }

    /**
     * Get shippingAddress
     *
     * @return AddressInterface
     */
    public function getShippingAddress()
    {
        return $this->shippingAddress;
    }

    /**
     * Add address
     *
     * @param  AddressInterface $address
     * @return User
     */
    public function addAddress(AddressInterface $address)
    {
        if (!$this->hasAddress($address)) {
            $this->addresses[] = $address;
        }

        return $this;
    }

    /**
     * Remove address
     *
     * @param AddressInterface $address
     */
    public function removeAddress(AddressInterface $address)
    {
        $this->addresses->removeElement($address);
    }

    /**
     * Has address
     *
     * @param  AddressInterface $address
     * @return bool
     */
    public function hasAddress(AddressInterface $address)
    {
        return $this->addresses->contains($address);
    }

    /**
     * Get addresses
     *
     * @return ArrayCollection
     */
    public function getAddresses()
    {
        return $this->addresses;
    }

    public function setFirstName($firstName)
    {
        $this->firstName = $firstName;
    }

    public function getFirstName()
    {
        return $this->firstName;
    }

    public function setLastName($lastName)
    {
        $this->lastName = $lastName;
    }

    public function getLastName()
    {
        return $this->lastName;
    }

    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    public function setCreatedAt(DateTime $createdAt)
    {
        $this->createdAt = $createdAt;
    }

    public function getUpdatedAt()
    {
        return $this->createdAt;
    }

    public function setUpdatedAt(DateTime $updatedAt)
    {
        $this->updatedAt = $updatedAt;
    }

    public function setEmail($email)
    {
        parent::setEmail($email);
        //$this->setUsername($email);

        return $this;
    }

    public function setEmailCanonical($emailCanonical)
    {
        parent::setEmailCanonical($emailCanonical);
        //$this->setUsernameCanonical($emailCanonical);

        return $this;
    }
    
    /**
     * Sets password salt
     * 
     * @param string $salt
     */
    public function setSalt($salt)
    {
        $this->salt = $salt;
    }
    
    public function isLegacyPassword()
    {
        return $this->legacyPassword;
    }

    public function setLegacyPassword($boolean)
    {
        $this->legacyPassword = (Boolean) $boolean;

        return $this;
    }
    
    /**
     * Set ID of Facebook account attached to the user
     *
     * @param string $facebookId
     * @return User
     */
    public function setFacebookId($facebookId) 
    {
        $this->facebookId = $facebookId;

        return $this;
    }

    /**
     * Get ID of Facebook account attached to the user
     *
     * @return string $facebookId
     */
    public function getFacebookId() 
    {
        return $this->facebookId;
    }
    
    /**
     * Set ID of Twitter account attached to the user
     *
     * @param string $twitterId
     * @return User
     */
    public function setTwitterId($twitterId) 
    {
        $this->twitterId = $twitterId;

        return $this;
    }

    /**
     * Get ID of Twitter account attached to the user
     *
     * @return string $twitterId
     */
    public function getTwitterId() 
    {
        return $this->twitterId;
    }

}
