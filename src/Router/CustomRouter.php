<?php // src/Router/CustomRouter.php

namespace App\Router;

use App\Repository\AppRepository;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Routing\Matcher\RequestMatcherInterface;
use Symfony\Component\Routing\RouterInterface;

class CustomRouter implements RouterInterface,RequestMatcherInterface
{

    public function __construct(private RouterInterface $router,private RequestStack $requestStack)
    {

    }

    public function generate($name, $parameters = [], $referenceType = self::ABSOLUTE_PATH) : string
    {
        if ($name === 'admin') {
            if ($this->requestStack->getCurrentRequest()->attributes->get('appId') && !isset($parameters['appId'])) {
                $appId = $this->requestStack->getCurrentRequest()->attributes->get('appId');
                $parameters['appId'] = $appId;
            }
        }

        return $this->router->generate($name, $parameters, $referenceType);
    }

    public function setContext(\Symfony\Component\Routing\RequestContext $context) : void
    {
        $this->router->setContext($context);
    }

    public function getContext() : \Symfony\Component\Routing\RequestContext
    {
        return $this->router->getContext();
    }

    public function getRouteCollection() : \Symfony\Component\Routing\RouteCollection
    {
        return $this->router->getRouteCollection();
    }

    public function match($pathinfo) : array
    {
        return $this->router->match($pathinfo);
    }

    public function matchRequest(\Symfony\Component\HttpFoundation\Request $request) : array
    {
        return $this->router->matchRequest($request);
    }
}