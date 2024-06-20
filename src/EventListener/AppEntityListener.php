<?php

namespace App\EventListener;

use App\Entity\App;
use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Events;
use Ramsey\Uuid\Uuid;
use Psr\Log\LoggerInterface;

class AppEntityListener implements EventSubscriber
{
    private LoggerInterface $logger;

    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }
    public function getSubscribedEvents()
    {
        return [
            Events::prePersist,
        ];
    }

    public function prePersist(LifecycleEventArgs $args)
    {
        $entity = $args->getObject();

        if (!$entity instanceof App) {
            return;
        }

        $this->logger->info('Creating a new App entity with UUID: '. $entity->getUid());



    }
}
