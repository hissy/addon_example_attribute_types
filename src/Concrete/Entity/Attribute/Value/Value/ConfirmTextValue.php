<?php
namespace Concrete\Package\ExampleAttributeTypes\Entity\Attribute\Value\Value;

use Concrete\Core\Entity\Attribute\Value\Value\Value;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="ConfirmTextAttributeValues")
 */
class ConfirmTextValue extends Value
{
    /**
     * @ORM\Column(type="string", nullable=true)
     */
    protected $value = '';

    /**
     * @var string This is not an ORM Column by design
     */
    protected $confirm = '';

    /**
     * @return mixed
     */
    public function getMainValue()
    {
        return $this->value;
    }

    /**
     * @return mixed
     */
    public function getConfirmValue()
    {
        return $this->confirm;
    }

    /**
     * @param mixed $value
     */
    public function setMainValue($value)
    {
        $this->value = $value;
    }

    /**
     * @param mixed $value
     */
    public function setConfirmValue($value)
    {
        $this->confirm = $value;
    }

    public function __toString()
    {
        return (string) $this->getMainValue();
    }
}
