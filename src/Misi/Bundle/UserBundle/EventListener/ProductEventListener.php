<?php
namespace Misi\Bundle\UserBundle\EventListener;

use FOS\UserBundle\FOSUserEvents;
use Symfony\Component\EventDispatcher\GenericEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Doctrine\ORM\EntityManager;
use Sylius\Bundle\CoreBundle\Model\Shop;
use Misi\Bundle\UserBundle\Fee\ProductFeeCalculator;
use Misi\Bundle\UserBundle\Model\UserFeeProduct;

/**
 * Listener responsible for handling user events like:
 * - shop creation on successfull registration
 */
class ProductEventListener implements EventSubscriberInterface
{
    /**
     * @var EntityManager 
     */
    protected $em;
    
    /**
     *
     * @var ProductFeeCalculator 
     */
    protected $productFeeCalculator;

    public function __construct(EntityManager $em, ProductFeeCalculator $productFeeCalculator)
    {
        $this->em = $em;
        $this->productFeeCalculator = $productFeeCalculator;
    }

    /**
     * {@inheritDoc}
     */
    public static function getSubscribedEvents()
    {
        return array(
            'sylius.product.create' => 'onProductCreate',
        );
    }

    public function onProductCreate(GenericEvent $event)
    {
        
        $product = $event->getSubject();
        
        $fee = $this->productFeeCalculator->calculateFee($product);
        
        $userProductFee = new UserFeeProduct();
        $userProductFee->setAmount($fee);
        $userProductFee->setProduct($product);
        $userProductFee->setUser($product->getShop()->getUser());
        
        $this->em->persist($userProductFee);
        $this->em->flush();

    }
}