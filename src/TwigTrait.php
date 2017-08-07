<?php
declare(strict_types=1);

namespace Fervo\ControllerTraits;

use Fervo\ControllerTraits\Exception\UninitializedTraitException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\StreamedResponse;

trait TwigTrait
{
    /**
     * @var \Twig_Environment
     * @internal
     */
    private $_trait_twig;

    /**
     * @internal
     */
    private function getTwig(): \Twig_Environment
    {
        if (!$this->_trait_twig) {
            throw new UninitializedTraitException("Did you forget to call setTwig?");
        }

        return $this->_trait_twig;
    }

    /**
     * @param \Twig_Environment $twig
     * @required
     * @internal
     */
    public function setTwig(\Twig_Environment $_trait_twig): void
    {
        $this->_trait_twig = $_trait_twig;
    }

    protected function renderView(string $name, array $context = []): string
    {
        return $this->getTwig()->render($name, $context);
    }

    /**
     * Renders a view.
     *
     * @param string   $view       The view name
     * @param array    $parameters An array of parameters to pass to the view
     * @param Response $response   A response instance
     *
     * @return Response A Response instance
     */
    protected function render(string $view, array $parameters = [], ?Response $response = null): Response
    {
        if (null === $response) {
            $response = new Response();
        }

        $response->setContent($this->renderView($view, $parameters));

        return $response;
    }

    /**
     * Streams a view.
     *
     * @param string           $view       The view name
     * @param array            $parameters An array of parameters to pass to the view
     * @param StreamedResponse $response   A response instance
     *
     * @return StreamedResponse A StreamedResponse instance
     */
    protected function stream(string $view, array $parameters = [], ?StreamedResponse $response = null): StreamedResponse
    {
        $twig = $this->getTwig();
        $callback = function () use ($twig, $view, $parameters) {
            $twig->display($view, $parameters);
        };

        if (null === $response) {
            return new StreamedResponse($callback);
        }

        $response->setCallback($callback);

        return $response;
    }
}
