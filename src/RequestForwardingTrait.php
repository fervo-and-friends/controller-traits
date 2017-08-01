<?php
declare(strict_types=1);

namespace Fervo\ControllerTraits;


use Fervo\ControllerTraits\Exception\UninitializedTraitException;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\HttpKernelInterface;

trait RequestForwardingTrait
{
    /**
     * @var RequestStack
     * @internal
     */
    private $_trait_requestStack;

    /**
     * @var HttpKernelInterface
     * @internal
     */
    private $_trait_httpKernel;

    /**
     * @internal
     */
    private function getRequestStack(): RequestStack
    {
        if (!$this->_trait_requestStack) {
            throw new UninitializedTraitException("Did you forget to initialize the trait");
        }

        return $this->_trait_requestStack;
    }

    /**
     * @internal
     */
    private function getHttpKernel(): HttpKernelInterface
    {
        if (!$this->_trait_httpKernel) {
            throw new UninitializedTraitException("Did you forget to initialize the trait");
        }

        return $this->_trait_httpKernel;
    }

    /**
     * @required
     * @internal
     */
    public function initializeRequestForwardingTrait(RequestStack $_trait_requestStack, HttpKernelInterface $_trait_httpKernel): void
    {
        $this->_trait_requestStack = $_trait_requestStack;
        $this->_trait_httpKernel = $_trait_httpKernel;
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
