<?php
declare(strict_types=1);

namespace Fervo\ControllerTraits;

use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

trait SecurityAuthorizationTrait
{
    /**
     * @var  AuthorizationCheckerInterface
     * @internal
     */
    protected $_trait_authorizationChecker;

    /**
     * @internal
     */
    private function getAuthorizationChecker(): AuthorizationCheckerInterface
    {
        if (!$this->_trait_authorizationChecker) {
            throw new UninitializedTraitException("Did you forget to initialize SecurityAuthorizationTrait?");
        }

        return $this->_trait_authorizationChecker;
    }

    /**
     * @internal
     * @required
     */
    public function setAuthorizationChecker(AuthorizationCheckerInterface $_trait_authorizationChecker)
    {
        $this->_trait_authorizationChecker = $_trait_authorizationChecker;
    }

    /**
     * Checks if the attributes are granted against the current authentication token and optionally supplied subject.
     *
     * @param mixed $attributes The attributes
     * @param mixed $subject    The subject
     *
     * @return bool
     *
     * @throws \LogicException
     */
    protected function isGranted($attributes, $subject = null)
    {
        return $this->getAuthorizationChecker()->isGranted($attributes, $subject);
    }
    /**
     * Throws an exception unless the attributes are granted against the current authentication token and optionally
     * supplied subject.
     *
     * @param mixed  $attributes The attributes
     * @param mixed  $subject    The subject
     * @param string $message    The message passed to the exception
     *
     * @throws AccessDeniedException
     */
    protected function denyAccessUnlessGranted($attributes, $subject = null, $message = 'Access Denied.')
    {
        if (!$this->isGranted($attributes, $subject)) {
            $exception = $this->createAccessDeniedException($message);
            $exception->setAttributes($attributes);
            $exception->setSubject($subject);
            throw $exception;
        }
    }

    /**
     * Returns an AccessDeniedException.
     *
     * This will result in a 403 response code. Usage example:
     *
     *     throw $this->createAccessDeniedException('Unable to access this page!');
     *
     * @param string          $message  A message
     * @param \Exception|null $previous The previous exception
     *
     * @return AccessDeniedException
     */
    private function createAccessDeniedException($message = 'Access Denied.', \Exception $previous = null)
    {
        return new AccessDeniedException($message, $previous);
    }
}
