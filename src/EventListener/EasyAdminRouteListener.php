<?php
namespace App\EventListener;

use Monolog\Logger;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class EasyAdminRouteListener
{
    public function __construct(public Logger $logger)
    {
    }

    public function onKernelRequest(RequestEvent $event)
    {
        $this->logger->info('onKernelRequest');
        $request = $event->getRequest();
        $appId = $request->attributes->get('appId');
        $this->logger->info('appId: '.$appId);
        if (!$appId) {
            return;
        }

        // Vérifiez si le chemin correspond à une route EasyAdmin
        $pathInfo = $request->getPathInfo();
        if (preg_match('#^/'.$appId.'/admin#', $pathInfo)) {
            $this->logger->info('Matched');
            // Ajouter le paramètre 'appId' à la requête
            $request->attributes->set('appId', $appId);

        } else {
            throw new NotFoundHttpException('Application not found');
        }
    }
}
