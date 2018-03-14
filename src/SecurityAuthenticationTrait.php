<?php
declare(strict_types=1);

namespace Fervo\ControllerTraits;

use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;

trait SecurityAuthenticationTrait
{
    /**
     * @var  TokenStorageInterface
     * @internal
     */
    protected $_trait_tokenStorage;

    /**
     * @internal
     */
    private function getTokenStorage(): TokenStorageInterface
    {
        if (!$this->_trait_tokenStorage) {
            throw new UninitializedTraitException("Did you forget to initialize SecurityAuthenticationTrait?");
        }

        return $this->_trait_tokenStorage;
    }

    /**
     * @required
     * @internal
     */
    public function setTokenStorage(TokenStorageInterface $_trait_tokenStorage)
    {
        $this->_trait_tokenStorage = $_trait_tokenStorage;
    }

    
    /**
     * Get the current token from the Security Token Storage
     *
     * @throws \LogicException If SecurityBundle is not available
     */
    protected function getToken(): ?TokenInterface
    {
        return $this->getTokenStorage()->getToken();
    }
    
    /**
     * Get a user from the Security Token Storage.
     *
     * @return mixed
     *
     * @throws \LogicException If SecurityBundle is not available
     *
     * @see TokenInterface::getUser()
     */
    protected function getUser()
    {
        if (null === $token = $this->getTokenStorage()->getToken()) {
            return;
        }
        if (!is_object($user = $token->getUser())) {
            // e.g. anonymous authentication
            return;
        }
        return $user;
    }
}
