<?php

namespace Misi\Bundle\MessageBundle\Model;

use Doctrine\Common\Collections\ArrayCollection;
use FOS\MessageBundle\Entity\Message as BaseMessage;
use FOS\MessageBundle\Model\ThreadInterface;
use FOS\MessageBundle\Model\ParticipantInterface;
use FOS\MessageBundle\Model\MessageMetadata as ModelMessageMetadata;

class Message extends BaseMessage
{
    protected $id;
    
    protected $thread;
    
    protected $sender;
    
    protected $metadata;

    public function __construct()
    {
        parent::__construct();

        $this->metadata = new ArrayCollection();
    }

    public function setThread(ThreadInterface $thread)
    {
        $this->thread = $thread;
        return $this;
    }

    public function setSender(ParticipantInterface $sender)
    {
        $this->sender = $sender;
        return $this;
    }

    public function addMetadata(ModelMessageMetadata $meta)
    {
        $meta->setMessage($this);
        parent::addMetadata($meta);
    }

}