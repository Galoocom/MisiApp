<?php

namespace Misi\Bundle\MessageBundle\Model;

use FOS\MessageBundle\Entity\ThreadMetadata as BaseThreadMetadata;
use FOS\MessageBundle\Model\ThreadInterface;
use FOS\MessageBundle\Model\ParticipantInterface;

class ThreadMetadata extends BaseThreadMetadata
{
    protected $id;

    protected $thread;

    protected $participant;

    public function setThread(ThreadInterface $thread) {
        $this->thread = $thread;
    }

    public function setParticipant(ParticipantInterface $participant) {
        $this->participant = $participant;
        return $this;
    }
}
