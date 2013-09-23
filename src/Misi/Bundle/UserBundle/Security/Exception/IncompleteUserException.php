<?php

namespace Misi\Bundle\UserBundle\Security\Exception;

use Symfony\Component\Security\Core\Exception\AuthenticationException;
use HWI\Bundle\OAuthBundle\Security\Core\Exception\OAuthAwareExceptionInterface;

/**
 * IncompleteUserException is thrown when the user isn't fully registered (e.g.: missing some informations).
 */
class IncompleteUserException extends AuthenticationException implements OAuthAwareExceptionInterface
{
    private $user;
    private $accessToken;
    private $resourceOwnerName;

    /**
     * {@inheritdoc}
     */
    public function setAccessToken($accessToken)
    {
        $this->accessToken = $accessToken;
    }

    /**
     * {@inheritdoc}
     */
    public function getAccessToken()
    {
        return $this->accessToken;
    }

    /**
     * {@inheritdoc}
     */
    public function getResourceOwnerName()
    {
        return $this->resourceOwnerName;
    }

    /**
     * {@inheritdoc}
     */
    public function setResourceOwnerName($resourceOwnerName)
    {
        $this->resourceOwnerName = $resourceOwnerName;
    }

    public function setUser($user)
    {
        $this->user = $user;
    }

    public function getUser($user)
    {
        return $this->user;
    }
    
    /**
     * {@inheritdoc}
     */
    public function getExpiresIn()
    {
        return $this->token->getExpiresIn();
    }

    /**
     * @return OAuthToken
     */
    public function getRawToken()
    {
        return $this->token->getRawToken();
    }   
    
    /**
     * {@inheritdoc}
     */
    public function getRefreshToken()
    {
        return $this->token->getRefreshToken();
    }
    
    /**
     * {@inheritdoc}
     */
    public function getTokenSecret()
    {
        return $this->token->getTokenSecret();
    }    
    
    public function serialize()
    {
        return serialize(array(
            $this->user,
            $this->accessToken,
            $this->resourceOwnerName,
            parent::serialize(),
        ));
    }

    public function unserialize($str)
    {
        list(
            $this->user,
            $this->accessToken,
            $this->resourceOwnerName,
            $parentData
        ) = unserialize($str);
        parent::unserialize($parentData);
    }
}