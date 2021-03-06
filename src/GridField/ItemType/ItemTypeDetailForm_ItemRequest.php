<?php
namespace WebbuildersGroup\GridField\ItemType;

use SilverStripe\Forms\GridField\GridFieldDetailForm_ItemRequest;
use SilverStripe\Control\Controller;

class ItemTypeDetailForm_ItemRequest extends GridFieldDetailForm_ItemRequest
{
    private static $allowed_actions = [
        'ItemEditForm'
    ];
    
    /**
     * Generates the form with the item type in the action url
     * @return Form
     */
    public function ItemEditForm()
    {
        $form = parent::ItemEditForm();
        
        if ($this->record) {
            if (!$this->record->exists() && $this->request->getVar('ItemType')) {
                if ($addButton = $this->gridField->getConfig()->getComponentByType(AddNewItemTypeButton::class)) {
                    $values = $addButton->getRawDropdownValues();
                    
                    if (!array_key_exists($this->request->getVar('ItemType'), $values)) {
                        user_error('The item type "' . htmlentities($this->request->getVar('ItemType')) . '" is not one of the available item types', E_USER_ERROR);
                    }
                    
                    $form->setFormAction(Controller::join_links($form->FormAction(), '?ItemType=' . rawurlencode($this->request->getVar('ItemType'))));
                } else {
                    user_error('You must have the GridField Component "' . AddNewItemTypeButton::class . '" in your GridField config', E_USER_ERROR);
                }
            } else if (!$this->record->exists()) {
                user_error('No item type selected', E_USER_ERROR);
            }
        }
        
        return $form;
    }
}
