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

use Sylius\Bundle\CoreBundle\Model\ImageInterface;
use Sylius\Bundle\TaxonomiesBundle\Model\Taxon as BaseTaxon;
use SplFileInfo;
use DateTime;

class Taxon extends BaseTaxon implements ImageInterface
{
    /**
     * @var SplFileInfo
     */
    protected $file;

    /**
     * @var string
     */
    protected $path;

    /**
     * @var \DateTime
     */
    protected $createdAt;

    /**
     * @var \DateTime
     */
    protected $updatedAt;
    
    /**
     * @var string
     */
    protected $pageTitle;
    
    /**
     * @var string
     */
    protected $metaTitle;

    /**
     * @var string
     */
    protected $metaDescription;

    /**
     * @var string
     */
    protected $metaKeywords;    

    public function __construct()
    {
        parent::__construct();

        $this->createdAt = new DateTime();
    }

    public function hasFile()
    {
        return null !== $this->file;
    }

    public function getFile()
    {
        return $this->file;
    }

    public function setFile(SplFileInfo $file)
    {
        $this->file = $file;
    }

    public function hasPath()
    {
        return null !== $this->path;
    }

    public function getPath()
    {
        return $this->path;
    }

    public function setPath($path)
    {
        $this->path = $path;
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
        return $this->updatedAt;
    }

    public function setUpdatedAt(DateTime $updatedAt)
    {
        $this->updatedAt = $updatedAt;
    }
    
    public function getMetaTitle()
    {
        return $this->metaTitle;
    }

    public function setMetaTitle($metaTitle)
    {
        $this->metaTitle = $metaTitle;
    }

    public function getMetaDescription()
    {
        return $this->metaDescription;
    }

    public function setMetaDescription($metaDescription)
    {
        $this->metaDescription = $metaDescription;
    }

    public function getMetaKeywords()
    {
        return $this->metaKeywords;
    }

    public function setMetaKeywords($metaKeywords)
    {
        $this->metaKeywords = $metaKeywords;
    }
    
    public function getPageTitle()
    {
        return $this->pageTitle;
    }

    public function setPageTitle($pageTitle)
    {
        $this->pageTitle = $pageTitle;
    }    
}
