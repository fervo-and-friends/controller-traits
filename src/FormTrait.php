<?php
declare(strict_types=1);

namespace Fervo\ControllerTraits;

use Fervo\ControllerTraits\Exception\UninitializedTraitException;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;

trait FormTrait
{
    /**
     * @var FormFactory
     * @internal
     */
    private $_trait_formFactory;

    /**
     * @internal
     */
    private function getFormFactory(): FormFactoryInterface
    {
        if (!$this->_trait_formFactory) {
            throw new UninitializedTraitException("Did you forget to call setFormFactory?");
        }

        return $this->_trait_formFactory;
    }

    /**
     * @param FormFactory $formFactory
     * @required
     * @internal
     */
    public function setFormFactory(FormFactoryInterface $_trait_formFactory): void
    {
        $this->_trait_formFactory = $_trait_formFactory;
    }


    /**
     * Creates and returns a Form instance from the type of the form.
     *
     * @param string $type    The fully qualified class name of the form type
     * @param mixed  $data    The initial data for the form
     * @param array  $options Options for the form
     *
     * @return FormInterface
     */
    protected function createForm(string $type, $data = null, array $options = []): FormInterface
    {
        return $this->getFormFactory()->create($type, $data, $options);
    }

    /**
     * Creates and returns a form builder instance.
     *
     * @param mixed $data    The initial data for the form
     * @param array $options Options for the form
     *
     * @return FormBuilderInterface
     */
    protected function createFormBuilder($data = null, array $options = []): FormBuilderInterface
    {
        return $this->getFormFactory()->createBuilder(FormType::class, $data, $options);
    }
}
