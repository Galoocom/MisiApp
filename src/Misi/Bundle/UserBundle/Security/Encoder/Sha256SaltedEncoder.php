<?php

namespace Misi\Bundle\UserBundle\Security\Encoder;
 
use Symfony\Component\Security\Core\Encoder\PasswordEncoderInterface;
 
class Sha256SaltedEncoder implements PasswordEncoderInterface 
{
    public function encodePassword( $raw, $salt ) {
        return hash('sha256', hash('sha256', $raw) . $salt);        
    }
 
    public function isPasswordValid( $encoded, $raw, $salt ) {
        return $encoded === $this->encodePassword( $raw, $salt );
    }
 
}