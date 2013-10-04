<?php

namespace Misi\Bundle\MessageBundle\Model;

use Doctrine\Common\Collections\ArrayCollection;

use FOS\MessageBundle\Entity\Thread as BaseThread;
use FOS\MessageBundle\Model\ParticipantInterface;
use FOS\MessageBundle\Model\MessageInterface;
use FOS\MessageBundle\Model\ThreadMetadata as ModelThreadMetadata;

class Thread extends BaseThread
{
    protected $id;

    protected $createdBy;

    protected $messages;

    protected $metadata;

    public function __construct()
    {
        parent::__construct();

        $this->messages = new ArrayCollection();
    }

    public function setCreatedBy(ParticipantInterface $participant) {
            $this->createdBy = $participant;
            return $this;
    }

    function addMessage(MessageInterface $message) {
            $this->messages->add($message);
    }

    public function addMetadata(ModelThreadMetadata $meta) {
        $meta->setThread($this);
        parent::addMetadata($meta);
    }

}