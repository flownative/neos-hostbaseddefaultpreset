<?php
namespace Flownative\Neos\HostBasedDefaultPreset\Aspects;

/*
 * This file is part of the Flownative.Neos.HostBasedDefaultPreset package.
 *
 * (c) 2017 Karsten Dambekalns, Flownative GmbH
 *
 * This package is Open Source Software. For the full copyright and license
 * information, please view the LICENSE file which was distributed with this
 * source code.
 */

use Neos\Flow\Annotations as Flow;
use Neos\Flow\Aop\JoinPointInterface;
use Neos\Flow\Core\Bootstrap;
use Neos\Flow\Http\HttpRequestHandlerInterface;

/**
 * @Flow\Scope("singleton")
 * @Flow\Aspect
 */
class RouteCacheAspect
{
    /**
     * @Flow\Inject
     * @var Bootstrap
     */
    protected $bootstrap;

    /**
     * Add the request host as a tag for the route cache entry
     *
     * @Flow\Around("method(Neos\Flow\Mvc\Routing\RouterCachingService->buildResolveCacheIdentifier())")
     * @param JoinPointInterface $joinPoint The current join point
     * @return array
     * @throws \Exception
     */
    public function addHostname(JoinPointInterface $joinPoint)
    {
        $resolveCacheIdentifier = $joinPoint->getAdviceChain()->proceed($joinPoint);

        $activeRequestHandler = $this->bootstrap->getActiveRequestHandler();
        if ($activeRequestHandler instanceof HttpRequestHandlerInterface) {
            $requestedHost = $activeRequestHandler->getHttpRequest()->getUri()->getHost();
            $resolveCacheIdentifier = md5(sprintf(
                '%s_%s',
                $resolveCacheIdentifier,
                $requestedHost
            ));
        }

        return $resolveCacheIdentifier;
    }
}
