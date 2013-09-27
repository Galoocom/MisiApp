<?php

namespace Misi\Bundle\UserBundle\EventListener;

use Symfony\Component\Security\Http\Event\InteractiveLoginEvent;
use FOS\UserBundle\EventListener\LastLoginListener as BaseLastLoginListener;
use FOS\UserBundle\Event\FilterUserResponseEvent;
use FOS\UserBundle\Model\UserInterface;
use Symfony\Component\HttpFoundation\Response;
use FOS\UserBundle\FOSUserEvents;

class LastLoginListener extends BaseLastLoginListener
{
    /**
     * If the user is authenticated but doesnt have id it means it came from oAuth
     * thus we need to trigger the appropriate events
     * 
     * @param use Symfony\Component\Security\Http\Event\InteractiveLoginEvent $event
     */
    public function onSecurityInteractiveLogin(InteractiveLoginEvent $event)
    {
        $user = $event->getAuthenticationToken()->getUser();

        $oAuthRegistration = ($user->getId() === null) ? true : false;
        
        if ($user instanceof UserInterface) {
            $user->setLastLogin(new \DateTime());
            $this->userManager->updateUser($user);
            
            if ($oAuthRegistration) {
                // create dummy response
                $response = new Response();
                $registrationEvent = new FilterUserResponseEvent($user, $event->getRequest(), $response);
                $event->getDispatcher()->dispatch(FOSUserEvents::REGISTRATION_COMPLETED, $registrationEvent);
            }
        }
    }    
}
?>
