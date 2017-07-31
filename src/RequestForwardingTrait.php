<?php
declare(strict_types=1);

namespace Fervo\ControllerTraits;


use Fervo\ControllerTraits\Exception\UninitializedTraitException;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\HttpKernelInterface;

trait RequestForwardingTrait
{
    /** @var RequestStack */
    private $requestStack;

    /** @var HttpKernelInterface */
    private $httpKernel;

    private function getRequestStack(): RequestStack
    {
        if (!$this->requestStack) {
            throw new UninitializedTraitException("Did you forget to initialize the trait");
        }

        return $this->requestStack;
    }

    private function getHttpKernel(): HttpKernelInterface
    {
        if (!$this->httpKernel) {
            throw new UninitializedTraitException("Did you forget to initialize the trait");
        }

        return $this->httpKernel;
    }

    /**
     * @required
     * @internal
     */
    public function initializeRequestForwardingTrait(RequestStack $requestStack, HttpKernelInterface $httpKernel): void
    {
        $this->requestStack = $requestStack;
        $this->httpKernel = $httpKernel;
    }


    /**
     * Forwards the request to another controller.
     *
     * @param string $controller The controller name (a string like BlogBundle:Post:index)
     * @param array  $path       An array of path parameters
     * @param array  $query      An array of query parameters
     *
     * @return Response A Response instance
     */
    protected function forward(string $controller, array $path = [], array $query = [])
    {
        $request = $this->getRequestStack()->getCurrentRequest();
        $path['_forwarded'] = $request->attributes;
        $path['_controller'] = $controller;
        $subRequest = $request->duplicate($query, null, $path);
        return $this->getHttpKernel()->handle($subRequest, HttpKernelInterface::SUB_REQUEST);
    }
}
