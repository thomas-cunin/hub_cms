<?php

namespace App\EventListener;

use Symfony\Component\HttpKernel\Event\RequestEvent;
use Psr\Log\LoggerInterface;

class LocaleListener
{
    private string $defaultLocale;
    private LoggerInterface $logger;

    public function __construct(LoggerInterface $logger, string $defaultLocale = 'en')
    {
        $this->defaultLocale = $defaultLocale;
        $this->logger = $logger;
    }

    public function onKernelRequest(RequestEvent $event)
    {
        $request = $event->getRequest();
        if (!$request->hasPreviousSession()) {
            $this->logger->info('No previous session found.');
            return;
        }

// On essaie de voir si la locale a été fixée dans la session
        if ($locale = $request->getSession()->get('_locale')) {
            $request->setLocale($locale);
            $this->logger->info('Locale set from session: ' . $locale);
        } else {
// Sinon on utilise celle par défaut
            $request->setLocale($this->defaultLocale);
            $this->logger->info('Locale set to default: ' . $this->defaultLocale);
        }
    }
}
