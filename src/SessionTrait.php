<?php
declare(strict_types=1);

namespace Fervo\ControllerTraits;


use Symfony\Component\HttpFoundation\Session\SessionInterface;

trait SessionTrait
{
    /**
     * @var Session
     * @internal
     */
    protected $_trait_session;

    /**
     * @internal
     */
    private function getSession(): SessionInterface
    {
        if (!isset($this->_trait_session)) {
            throw new UninitializedTraitException("Did you forget to call setSession?");
        }

        return $this->_trait_session;
    }

    /**
     * @required
     * @internal
     */
    public function setSession(SessionInterface $_trait_session)
    {
        $this->_trait_session = $_trait_session;
    }

    /**
     * Adds a flash message to the current session for type.
     *
     * @param string $type    The type
     * @param string $message The message
     *
     * @throws \LogicException
     */
    protected function addFlash($type, $message)
    {
        $this->getSession()->getFlashBag()->add($type, $message);
    }
}
