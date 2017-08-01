<?php
declare(strict_types=1);

namespace Fervo\ControllerTraits;


use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;

trait SecurityCsrfTrait
{
    /**
     * @var CsrfTokenManagerInterface
     * @internal
     */
    protected $_trait_csrfTokenManager;

    /**
     * @internal
     */
    private function getCsrfTokenManager(): CsrfTokenManagerInterface
    {
        if (!$this->_trait_csrfTokenManager) {
            throw new UninitializedTraitException("Did you forget to call setCsrfTokenManager?");
        }

        return $this->_trait_csrfTokenManager;
    }

    /**
     * @required
     * @internal
     */
    public function setCsrfTokenManager(CsrfTokenManagerInterface $_trait_csrfTokenManager)
    {
        $this->_trait_csrfTokenManager = $_trait_csrfTokenManager;
        $this->_trait_csrfTokenManager = $_trait_csrfTokenManager;
    }

    /**
     * Checks the validity of a CSRF token.
     *
     * @param string $id    The id used when generating the token
     * @param string $token The actual token sent with the request that should be validated
     *
     * @return bool
     */
    protected function isCsrfTokenValid($id, $token)
    {
        return $this->getCsrfTokenManager()->isTokenValid(new CsrfToken($id, $token));
    }
}
