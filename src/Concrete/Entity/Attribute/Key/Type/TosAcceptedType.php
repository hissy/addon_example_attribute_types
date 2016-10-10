<?php
namespace Concrete\Package\ExampleAttributeTypes\Entity\Attribute\Key\Type;

use Concrete\Core\Entity\Attribute\Key\Type\Type;
use Concrete\Core\Entity\Attribute\Value\Value\BooleanValue;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="TosAcceptedAttributeKeyTypes")
 */
class TosAcceptedType extends Type
{
    /**
     * @ORM\Column(type="boolean")
     */
    protected $akCheckedByDefault = false;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    protected $akLabelHTML = '';

    public function getAttributeTypeHandle()
    {
        return 'tos_accepted';
    }

    /**
     * @return boolean
     */
    public function isCheckedByDefault()
    {
        return $this->akCheckedByDefault;
    }

    /**
     * @param mixed $isCheckedByDefault
     */
    public function setIsCheckedByDefault($isCheckedByDefault)
    {
        $this->akCheckedByDefault = $isCheckedByDefault;
    }

    /**
     * @return mixed
     */
    public function getLabelHTML()
    {
        return ($this->akLabelHTML) ? $this->akLabelHTML : t('I Agree to Terms.');
    }

    /**
     * @param string $html
     */
    public function setLabelHTML($html)
    {
        $this->akLabelHTML = $html;
    }

    public function getAttributeValue()
    {
        return new BooleanValue();
    }

}
