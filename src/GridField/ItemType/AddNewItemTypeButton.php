<?php
namespace WebbuildersGroup\GridField\ItemType;

use SilverStripe\Control\Controller;
use SilverStripe\Forms\GridField\GridField_HTMLProvider;
use SilverStripe\Model\ArrayData;
use SilverStripe\Model\List\ArrayList;
use SilverStripe\ORM\ArrayList as LegacyArrayList;
use SilverStripe\View\ArrayData as LegacyArrayData;
use SilverStripe\View\Requirements;

/**
 * @package forms
 */
class AddNewItemTypeButton implements GridField_HTMLProvider
{
    protected $_targetFragment;

    protected $_buttonName;

    protected $_dropdownValues;
    protected $_rawDropdownValues;

    protected $_emptyLabel = null;
    protected $_default = null;

    /**
     * Target fragment to assign the button to
     * @param {array} Map of class name/label elements to use in the template each item should have a Class key and a Title key
     * @param {string} $targetFragment Target fragment to place the button in
     * @param {string} $emptyLabel Label for empty option, leave null to exclude
     * @param {string} $default The default value to select
     */
    public function __construct(array $dropdownValues, $targetFragment = 'before', $emptyLabel = null, $default = null)
    {
        $this->_targetFragment = $targetFragment;
        $this->_rawDropdownValues = $dropdownValues;

        $tmp = (class_exists(LegacyArrayList::class)) ? new LegacyArrayList() : new ArrayList();

        foreach ($this->_rawDropdownValues as $class => $label) {
            $values = [
                'Class' => $class,
                'Title' => $label,
            ];
            $arrayData = (class_exists(LegacyArrayData::class) ? new LegacyArrayData($values) : new ArrayData($values));

            $tmp->push($arrayData);
        }

        $this->_dropdownValues = $tmp;
        $this->_emptyLabel = $emptyLabel;
        $this->_default = $default;
    }

    /**
     * Sets the name of the button
     * @param {string} Label on the button
     * @return {AddNewItemTypeButton}
     */
    public function setButtonName($name)
    {
        $this->_buttonName = $name;

        return $this;
    }

    /**
     * Sets the empty option label
     * @param {string} $val Empty option value, set to null to remove
     * @return {AddNewItemTypeButton}
     */
    public function setEmptyLabel($val)
    {
        $this->_emptyLabel = $val;

        return $this;
    }

    /**
     * Gets the dropdown values
     * @return {array} Map of class name/label elements to use in the template each item should have a Class key and a Title key
     */
    public function getRawDropdownValues()
    {
        return $this->_rawDropdownValues;
    }

    /**
     * Returns a map where the keys are fragment names and the values are pieces of HTML to add to these fragments.
     * @return {array} Fragments to be used
     */
    public function getHTMLFragments($gridField)
    {
        if (!$this->_buttonName) {
            // provide a default button name, can be changed by calling {@link setButtonName()} on this component
            $this->_buttonName = _t('GridField.Add', 'Add {name}', ['name' => singleton($gridField->getModelClass())->i18n_singular_name()]);
        }


        $data = new ArrayData([
            'NewLink' => Controller::join_links($gridField->Link('item'), 'new'),
            'ButtonName' => $this->_buttonName,
            'EmptyLabel' => htmlentities($this->_emptyLabel),
            'DropdownValues' => $this->_dropdownValues,
            'Default' => $this->_default
        ]);



        Requirements::css('webbuilders-group/silverstripe-gridfielditemtype:css/AddNewItemTypeButton.css');
        Requirements::javascript('webbuilders-group/silverstripe-gridfielditemtype:javascript/AddNewItemTypeButton.js');

        return [
            $this->_targetFragment => $data->renderWith(AddNewItemTypeButton::class),
        ];
    }
}
