<?php
declare(strict_types=1);

namespace Fervo\ControllerTraits;


use Fervo\ControllerTraits\Exception\UninitializedTraitException;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

trait RoutingTrait
{
    /** @var UrlGeneratorInterface */
    private $urlGenerator;

    private function getUrlGenerator(): UrlGeneratorInterface
    {
        if (!$this->urlGenerator) {
            throw new UninitializedTraitException();
        }
        return $this->urlGenerator;
    }

    /**
     * @required
     * @internal
     */
    public function setUrlGenerator(UrlGeneratorInterface $urlGenerator): void
    {
        $this->urlGenerator = $urlGenerator;
    }



    /**
     * Generates a URL from the given parameters.
     *
     * @param string $route         The name of the route
     * @param mixed  $parameters    An array of parameters
     * @param int    $referenceType The type of reference (one of the constants in UrlGeneratorInterface)
     *
     * @return string The generated URL
     *
     * @see UrlGeneratorInterface
     */
    protected function generateUrl($route, $parameters = array(), $referenceType = UrlGeneratorInterface::ABSOLUTE_PATH)
    {
        return $this->getUrlGenerator()->generate($route, $parameters, $referenceType);
    }

    /**
     * Returns a RedirectResponse to the given route with the given parameters.
     *
     * @param string $route      The name of the route
     * @param array  $parameters An array of parameters
     * @param int    $status     The status code to use for the Response
     *
     * @return RedirectResponse
     */
    protected function redirectToRoute($route, array $parameters = array(), $status = 302)
    {
        return new RedirectResponse($this->generateUrl($route, $parameters), $status);
    }
}
