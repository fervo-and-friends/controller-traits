<?php
declare(strict_types=1);

namespace Fervo\ControllerTraits;

use Fervo\ControllerTraits\Exception\UninitializedTraitException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\StreamedResponse;

trait TwigTrait
{
    /** @var \Twig_Environment */
    private $twig;

    private function getTwig(): \Twig_Environment
    {
        if (!$this->twig) {
            throw new UninitializedTraitException("Did you forget to call setTwig?");
        }

        return $this->twig;
    }

    /**
     * @param \Twig_Environment $twig
     * @required
     * @internal
     */
    public function setTwig(\Twig_Environment $twig): void
    {
        $this->twig = $twig;
    }

    protected function render(string $name, array $context = []): string
    {
        return $this->twig->render($name, $context);
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
    protected function renderResponse(string $view, array $parameters = [], ?Response $response = null): Response
    {
        if (null === $response) {
            $response = new Response();
        }

        $response->setContent($this->render($view, $parameters));

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
