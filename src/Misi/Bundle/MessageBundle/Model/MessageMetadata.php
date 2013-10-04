<?php

namespace Misi\Bundle\MessageBundle\Model;

use FOS\MessageBundle\Entity\MessageMetadata as BaseMessageMetadata;
use FOS\MessageBundle\Model\MessageInterface;
use FOS\MessageBundle\Model\ParticipantInterface;

class MessageMetadata extends BaseMessageMetadata
{
    protected $id;
    
    protected $message;
    
    protected $participant;

    public function setMessage(MessageInterface $message)
    {
        $this->message = $message;
        return $this;
    }

    public function setParticipant(ParticipantInterface $participant)
    {
        $this->participant = $participant;
        return $this;
    }

}