<?php
// src/ValueResolver/AppValueResolver.php
namespace App\ValueResolver;

use App\Entity\App;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Controller\ValueResolverInterface;
use Symfony\Component\HttpKernel\ControllerMetadata\ArgumentMetadata;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class AppValueResolver implements ValueResolverInterface
{

    public function __construct(private EntityManagerInterface $entityManager)
    {
    }

    public function resolve(Request $request, ArgumentMetadata $argument): iterable
    {
        if (App::class !== $argument->getType()) {
            return [];
        }

        $subdomain = $this->getSubdomain($request);
        $host = $request->getHost();

        $app = $this->entityManager->getRepository(App::class)->findOneBy(['slug' => $subdomain]);

        if (!$app) {
            $app = $this->entityManager->getRepository(App::class)->findOneBy(['domain' => $host]);
        }

        if (!$app) {
            throw new NotFoundHttpException('App not found');
        }

        return [$app];
    }

    private function getSubdomain(Request $request)
    {
        $host = $request->getHost();
        $parts = explode('.', $host);
        return count($parts) > 2 ? $parts[0] : null;
    }
}
