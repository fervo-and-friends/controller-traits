<?php
declare(strict_types=1);

namespace Fervo\ControllerTraits;


class SessionTrait
{
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
        if (!$this->container->has('session')) {
            throw new \LogicException('You can not use the addFlash method if sessions are disabled.');
        }
        $this->container->get('session')->getFlashBag()->add($type, $message);
    }
}
