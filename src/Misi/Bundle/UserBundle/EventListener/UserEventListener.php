<?php
namespace Misi\Bundle\UserBundle\EventListener;

use FOS\UserBundle\FOSUserEvents;
use FOS\UserBundle\Event\FilterUserResponseEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Doctrine\ORM\EntityManager;
use Sylius\Bundle\CoreBundle\Model\Shop;

/**
 * Listener responsible for handling user events like:
 * - shop creation on successfull registration
 */
class UserEventListener implements EventSubscriberInterface
{
    protected $em;
    protected $user;

    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }

    /**
     * {@inheritDoc}
     */
    public static function getSubscribedEvents()
    {
        return array(
            FOSUserEvents::REGISTRATION_COMPLETED => 'onRegistrationCompleted',
        );
    }

    public function onRegistrationCompleted(FilterUserResponseEvent $event)
    {
        $user = $event->getUser();
        
        $shop = new Shop();
        
        $shop->setUser($user);
        $shop->setName(strtolower($user->getUsername()));
        $shop->setTitle($user->getUsername() . '`s shop');
        $shop->setDescription('Welcome to my Misi shop');
        $shop->setEnabled(true);
        $shop->setCreatedAt(new \DateTime());
        
        $user->setRoles(array('ROLE_USER'));

        $this->em->persist($shop);

        $this->em->flush();

    }
}