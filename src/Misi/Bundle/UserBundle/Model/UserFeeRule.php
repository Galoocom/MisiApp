<?php

namespace Misi\Bundle\UserBundle\Model;

use Sylius\Bundle\TaxonomiesBundle\Model\TaxonInterface;
use Sylius\Bundle\CoreBundle\Model\UserInterface;

/**
 * This is model for fees rules.
 * 
 * @author Laszlo Horvath <pentarim@gmail.com>
 */
class UserFeeRule
{
    const BASE_ABSOLUTE = 1;
    const BASE_PERCENT = 2;
    
    /**
     * Id.
     *
     * @var mixed
     */
    protected $id;
    
    /**
     * @var TaxonInterface 
     */
    protected $taxon;
    
    /**
     * @var UserInterface 
     */
    protected $user;
    
    /**
     * @var boolean
     */
    protected $enabled;

    /**
     * Base of calculation, if the amount is % of the item fee aplies to 
     * or an absolute amount
     *
     * @var integer
     */
    protected $base;

    /**
     * Amount of the fee (percent or absolute @see $this->base)
     *
     * @var integer
     */
    protected $amount;

    /**
     * Date from this rule applies
     *
     * @var \DateTime
     */
    protected $dateFrom;
    
    /**
     * Date to this rule applies
     *
     * @var \DateTime
     */
    protected $dateTo;
    
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
        $this->base = self::BASE_ABSOLUTE;
    }
    
    public function getId()
    {
        return $this->id;
    }

    public function getDateFrom()
    {
        return $this->dateFrom;
    }

    public function setDateFrom(\DateTime $dateFrom)
    {
        $this->dateFrom = $dateFrom;
    }

    public function getDateTo()
    {
        return $this->dateTo;
    }

    public function setDateTo(\DateTime $dateTo)
    {
        $this->dateTo = $dateTo;
    }
    
    public function isEnabled()
    {
        return $this->enabled;
    }
    
    public function setEnabled($boolean)
    {
        $this->enabled = (Boolean) $boolean;

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

    
    public function setTaxon(TaxonInterface $taxon)
    {
        $this->taxon = $taxon;
    }
    
    public function getTaxon()
    {
        return $this->taxon;
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
    
    public function getBase()
    {
        return $this->base;
    }

    public function setBase($base)
    {
        $this->base = $base;
    }
    
    public function calculateFee($amount)
    {
        return ($this->base === self::BASE_ABSOLUTE)
            ? $this->amount
            : ($this->amount * $amount) / 100;
        
    }
    
    
}