<?php
namespace Concrete\Package\ExampleAttributeTypes\Attribute\EmailConfirm;

use Concrete\Core\Attribute\DefaultController;
use Concrete\Core\Attribute\FontAwesomeIconFormatter;
use Concrete\Core\Entity\Attribute\Key\Key;
use Concrete\Core\Entity\Attribute\Key\Type\TextType;
use Concrete\Core\Error\ErrorList\Error\Error;
use Concrete\Core\Error\ErrorList\Error\FieldNotPresentError;
use Concrete\Core\Error\ErrorList\Field\AttributeField;
use Concrete\Core\Utility\Service\Validation\Strings;
use Concrete\Package\ExampleAttributeTypes\Entity\Attribute\Value\Value\ConfirmTextValue;

/**
 * Class Controller of "Email with Confirmation" Attribute Type.
 *
 * @package Concrete\Package\ExampleAttributeTypes\Attribute\EmailConfirm
 */
class Controller extends DefaultController
{
    /** @var string */
    protected $akTextPlaceholder;

    /**
     * Display form to save key.
     *
     * @see Concrete\Core\Attribute\Context\AttributeTypeSettingsContext::__construct()
     */
    public function type_form()
    {
        $this->set('form', $this->app->make('helper/form'));
        $this->load();
    }

    /**
     * Save key from Request.
     *
     * @see Concrete\Core\Attribute\Category\AbstractCategory::addFromRequest()
     *
     * @param $data Request data
     *
     * @return \Concrete\Core\Entity\Attribute\Key\Type\TextType
     */
    public function saveKey($data)
    {
        $type = $this->getAttributeKeyType();
        $data += [
            'akTextPlaceholder' => null,
        ];
        $akTextPlaceholder = $data['akTextPlaceholder'];

        $type->setPlaceholder($akTextPlaceholder);

        return $type;
    }

    /**
     * Load attribute type options.
     *
     * @return bool|null
     */
    protected function load()
    {
        /** @var Key $ak */
        $ak = $this->getAttributeKey();
        if (!is_object($ak)) {
            return false;
        }

        /** @var TextType $type */
        $type = $ak->getAttributeKeyType();
        $this->akTextPlaceholder = $type->getPlaceholder();
        $this->set('akTextPlaceholder', $type->getPlaceholder());
    }

    /**
     * Display form to save value.
     *
     * @see Concrete\Core\Attribute\Context\BasicFormContext::__construct()
     */
    public function form()
    {
        $this->load();
        $value = null;
        $confirm = null;
        if (is_object($this->attributeValue)) {
            /** @var ConfirmTextValue $v */
            $v = $this->getAttributeValue()->getValue();
            $value = $this->app->make('helper/text')->entities($v->getMainValue());
            $confirm = $this->app->make('helper/text')->entities($v->getConfirmValue());
        }

        /** @var Key $ak */
        $ak = $this->getAttributeKey();

        echo $this->app->make('helper/form')->email(
            $this->field('value'), $value, ['placeholder' => $this->akTextPlaceholder]
        );
        echo $this->app->make('helper/form')->label(
            $this->field('value_confirm'), t('Confirm %s', $ak->getAttributeKeyDisplayName())
        );
        echo $this->app->make('helper/form')->email(
            $this->field('value_confirm'), $confirm, ['placeholder' => $this->akTextPlaceholder]
        );
    }

    /**
     * Display form to save value on composer.
     *
     * @see Concrete\Core\Attribute\Context\StandardFormContext::__construct()
     */
    public function composer()
    {
        $value = null;
        $confirm = null;
        if (is_object($this->attributeValue)) {
            /** @var ConfirmTextValue $v */
            $v = $this->getAttributeValue()->getValue();
            $value = $this->app->make('helper/text')->entities($v->getMainValue());
            $confirm = $this->app->make('helper/text')->entities($v->getConfirmValue());
        }

        /** @var Key $ak */
        $ak = $this->getAttributeKey();

        echo $this->app->make('helper/form')->email(
            $this->field('value'), $value, ['class' => 'span5', 'placeholder' => $this->akTextPlaceholder]
        );
        echo $this->app->make('helper/form')->label(
            $this->field('value_confirm'), t('Confirm %s', $ak->getAttributeKeyDisplayName())
        );
        echo $this->app->make('helper/form')->email(
            $this->field('value_confirm'), $confirm, ['class' => 'span5', 'placeholder' => $this->akTextPlaceholder]
        );
    }

    /**
     * Create a new Attribute Value Object.
     *
     * @param $data
     *
     * @see Concrete\Core\Attribute\ObjectTrait::setAttribute()
     *
     * @return ConfirmTextValue
     */
    public function createAttributeValue($data)
    {
        extract($data);
        $av = new ConfirmTextValue();
        $av->setMainValue($value);
        $av->setConfirmValue($value_confirm);

        return $av;
    }

    /**
     * Create Attribute Value Object from Request.
     *
     * @see Concrete\Core\Express\Form\Control\SaveHandler\AttributeKeySaveHandler::saveFromRequest()
     * @see Concrete\Core\Page\Type\Composer\Control\CollectionAttributeControl::publishToPage()
     *
     * @return ConfirmTextValue
     */
    public function createAttributeValueFromRequest()
    {
        return $this->createAttributeValue($this->post());
    }

    /**
     * Validation for requirement check.
     *
     * @see Concrete\Core\Attribute\Value\Value\Value#validateAttributeValue()
     *
     * @return bool
     */
    public function validateValue()
    {
        $v = $this->getValue();
        if (!is_object($v)) {
            return false;
        }
        $vals = $this->app->make('helper/validation/strings');

        return $vals->notempty((string) $v);
    }

    /**
     * Validation for save form.
     *
     * @see Concrete\Core\Attribute\StandardValidator::validateSaveValueRequest()
     *
     * @param $data Post request
     *
     * @return bool|Error|FieldNotPresentError
     */
    public function validateForm($data)
    {
        if (!$data['value']) {
            return new FieldNotPresentError(new AttributeField($this->getAttributeKey()));
        } else {
            /** @var Strings $fh */
            $fh = $this->app->make('helper/validation/strings');
            if (!$fh->email($data['value'])) {
                return new Error(t('Invalid email address.'), new AttributeField($this->getAttributeKey()));
            } elseif ($data['value'] !== $data['value_confirm']) {
                return new Error(t('Please make sure your emails match.'), new AttributeField($this->getAttributeKey()));
            } else {
                return true;
            }
        }
    }

    /**
     * Export options to xml element.
     *
     * @param $akey
     *
     * @see Concrete\Core\Export\Item\AttributeKey::export()
     *
     * @return \SimpleXMLElement
     */
    public function exportKey($akey)
    {
        $this->load();
        $akey->addChild('type')->addAttribute('placeholder', $this->akTextPlaceholder);

        return $akey;
    }

    /**
     * Import options from xml element.
     *
     * @param \SimpleXMLElement $akey
     *
     * @see \Concrete\Core\Attribute\Category\AbstractCategory::import()
     *
     * @return TextType
     */
    public function importKey(\SimpleXMLElement $akey)
    {
        $type = $this->getAttributeKeyType();
        if (isset($akey->type)) {
            $data['akTextPlaceholder'] = $akey->type['placeholder'];
            $type->setPlaceholder((string) $akey->type['placeholder']);
        }

        return $type;
    }

    /**
     * Get icon formatter.
     *
     * @see \Concrete\Core\Express\Form\Control\Type\Item\AttributeKeyItem::getIcon()
     *
     * @return FontAwesomeIconFormatter
     */
    public function getIconFormatter()
    {
        return new FontAwesomeIconFormatter('envelope-o');
    }
}
