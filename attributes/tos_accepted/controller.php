<?php
namespace Concrete\Package\ExampleAttributeTypes\Attribute\TosAccepted;

use Concrete\Core\Attribute\FontAwesomeIconFormatter;
use Concrete\Core\Entity\Attribute\Key\Key;
use Concrete\Core\Entity\Attribute\Key\Type\BooleanType;
use Concrete\Core\Entity\Attribute\Value\Value\BooleanValue;
use Concrete\Core\Search\ItemList\Database\AttributedItemList;
use Concrete\Core\Search\ItemList\ItemList;
use Concrete\Package\ExampleAttributeTypes\Entity\Attribute\Key\Type\TosAcceptedType;

/**
 * Class Controller of "I Agree to Terms" Attribute Type.
 *
 * @package Concrete\Package\ExampleAttributeTypes\Attribute\TosAccepted
 */
class Controller extends \Concrete\Core\Attribute\Controller
{
    /** @var array */
    protected $searchIndexFieldDefinition = ['type' => 'boolean', 'options' => ['default' => 0, 'notnull' => false]];

    /** @var bool */
    protected $akCheckedByDefault;

    /** @var string */
    protected $akLabelHTML;

    /**
     * Get value for context of search indexing.
     *
     * @see \Concrete\Core\Entity\Attribute\Value\Value::getSearchIndexValue()
     *
     * @return int
     */
    public function getSearchIndexValue()
    {
        return $this->attributeValue->getValue() ? 1 : 0;
    }

    /**
     * Filter ItemList by this attribute type key from request value.
     *
     * @param ItemList $list
     *
     * @see \Concrete\Core\Search\Field\AttributeKeyField::filterList()
     *
     * @return ItemList
     */
    public function searchForm($list)
    {
        $list->filterByAttribute($this->attributeKey->getAttributeKeyHandle(), $this->request('value'));

        return $list;
    }

    /**
     * Filter ItemList by value.
     *
     * @param AttributedItemList $list
     * @param bool $boolean
     * @param string $comparison
     *
     * @see \Concrete\Core\Search\ItemList\Database\AttributedItemList::filterByAttribute()
     */
    public function filterByAttribute(AttributedItemList $list, $boolean, $comparison = '=')
    {
        $qb = $list->getQueryObject();
        $column = sprintf('ak_%s', $this->attributeKey->getAttributeKeyHandle());
        switch ($comparison) {
            case '<>':
            case '!=':
                $boolean = $boolean ? false : true;
                break;
        }
        if ($boolean) {
            $qb->andWhere("{$column} = 1");
        } else {
            $qb->andWhere("{$column} <> 1 or {$column} is null");
        }
    }

    /**
     * Display form to save key.
     *
     * @see Concrete\Core\Attribute\Context\AttributeTypeSettingsContext::__construct()
     */
    public function type_form()
    {
        $this->set('form', $this->app->make('helper/form'));
        $this->set('editor', $this->app->make('editor'));
        $this->load();
    }

    /**
     * Create a new Attribute Key Type object.
     *
     * @see \Concrete\Core\Attribute\Controller::getAttributeKeyType()
     *
     * @return TosAcceptedType
     */
    public function createAttributeKeyType()
    {
        return new TosAcceptedType();
    }

    /**
     * Save key from Request.
     *
     * @see Concrete\Core\Attribute\Category\AbstractCategory::addFromRequest()
     *
     * @param $data Request data
     *
     * @return TosAcceptedType
     */
    public function saveKey($data)
    {
        /** @var TosAcceptedType $type */
        $type = $this->getAttributeKeyType();

        $akCheckedByDefault = 0;
        if (isset($data['akCheckedByDefault']) && $data['akCheckedByDefault']) {
            $akCheckedByDefault = 1;
        }

        $type->setIsCheckedByDefault($akCheckedByDefault);
        $type->setLabelHTML($data['akLabelHTML']);

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

        /** @var TosAcceptedType $type */
        $type = $ak->getAttributeKeyType();
        $this->akCheckedByDefault = $type->isCheckedByDefault();
        $this->akLabelHTML = $type->getLabelHTML();
        $this->set('akCheckedByDefault', $this->akCheckedByDefault);
        $this->set('akLabelHTML', $this->akLabelHTML);
    }

    /**
     * Display form to save value.
     *
     * @see Concrete\Core\Attribute\Context\BasicFormContext::__construct()
     */
    public function form()
    {
        $this->load();
        $label = $this->akLabelHTML;
        $checked = false;
        if (is_object($this->attributeValue)) {
            /** @var BooleanValue $value */
            $value = $this->getAttributeValue()->getValue();
            $checked = $value == 1 ? true : false;
        } else {
            if ($this->akCheckedByDefault) {
                $checked = true;
            }
        }
        $this->set('label', $label);
        $this->set('checked', $checked);
    }

    /**
     * Create a new Attribute Value Object.
     *
     * @param $data
     *
     * @see Concrete\Core\Attribute\ObjectTrait::setAttribute()
     *
     * @return BooleanValue
     */
    public function createAttributeValue($value)
    {
        $v = new BooleanValue();
        $value = ($value == false || $value == '0') ? false : true;
        $v->setValue($value);

        return $v;
    }

    /**
     * Create Attribute Value Object from Request.
     *
     * @see Concrete\Core\Express\Form\Control\SaveHandler\AttributeKeySaveHandler::saveFromRequest()
     * @see Concrete\Core\Page\Type\Composer\Control\CollectionAttributeControl::publishToPage()
     *
     * @return BooleanValue
     */
    public function createAttributeValueFromRequest()
    {
        $data = $this->post();

        return $this->createAttributeValue(isset($data['value']) ? $data['value'] : false);
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

        return $v == 1;
    }

    /**
     * Validation for save form.
     *
     * @see Concrete\Core\Attribute\StandardValidator::validateSaveValueRequest()
     *
     * @param $data Post request
     *
     * @return bool
     */
    public function validateForm($data)
    {
        return isset($data['value']) && $data['value'] == 1;
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
        $akey->addChild('type')->addAttribute('checked', $this->akCheckedByDefault);

        return $akey;
    }

    /**
     * Import options from xml element.
     *
     * @param \SimpleXMLElement $akey
     *
     * @see \Concrete\Core\Attribute\Category\AbstractCategory::import()
     *
     * @return BooleanType
     */
    public function importKey(\SimpleXMLElement $akey)
    {
        /** @var TosAcceptedType $type */
        $type = $this->getAttributeKeyType();
        if (isset($akey->type)) {
            $checked = (string) $akey->type['checked'];
            if ($checked != '') {
                $type->setIsCheckedByDefault(true);
            }
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
        return new FontAwesomeIconFormatter('check-square-o');
    }
}
