<?php
class ItemTypeDetailForm extends GridFieldDetailForm {
    /**
     * Handles the request for an item
     * @param {GridField} $gridField Grid Field reference
     * @param {SS_HTTPRequest} $request HTTP Request
     * @return {mixed} Returns the result of handleRequest on the item request handler
     */
    public function handleItem($gridField, $request) {
        $controller=$gridField->getForm()->Controller();
        
        if(is_numeric($request->param('ID'))) {
            $record=$gridField->getList()->byId($request->param("ID"));
        }else if($request->getVar('ItemType')) {
            if($request->getVar('ItemType')==$gridField->getModelClass() || is_subclass_of($request->getVar('ItemType'), $gridField->getModelClass())) {
                $record=SS_Object::create($request->getVar('ItemType'));
            }else {
                user_error('Class '.$request->getVar('ItemType').' is not a sub class of '.$gridField->getModelClass(), E_USER_ERROR);
            }
        }else {
            user_error('No item type selected', E_USER_ERROR);
        }
        
        $class=$this->getItemRequestClass();
        
        $handler=SS_Object::create($class, $gridField, $this, $record, $controller, $this->name);
        $handler->setTemplate($this->template);

		// if no validator has been set on the GridField and the record has a
		// CMS validator, use that.
		if(!$this->getValidator() && (method_exists($record, 'getCMSValidator') || $record instanceof SS_Object && $record->hasMethod('getCMSValidator'))) {
			$this->setValidator($record->getCMSValidator());
		}
        
        return $handler->handleRequest($request, DataModel::inst());
    }
}

class ItemTypeDetailForm_ItemRequest extends GridFieldDetailForm_ItemRequest {
    private static $allowed_actions=array(
                                        'ItemEditForm'
                                    );
    
    /**
     * Generates the form with the item type in the action url
     * @return Form
     */
    public function ItemEditForm() {
        $form=parent::ItemEditForm();
        
        if($this->record) {
            if(!$this->record->exists() && $this->request->getVar('ItemType')) {
                if($addButton=$this->gridField->getConfig()->getComponentByType('AddNewItemTypeButton')) {
                    $values=$addButton->getRawDropdownValues();
                    
                    if(!array_key_exists($this->request->getVar('ItemType'), $values)) {
                        user_error('The item type "'.htmlentities($request->getVar('ItemType')).'" is not one of the available item types', E_USER_ERROR);
                    }
                    
                    $form->setFormAction(Controller::join_links($form->FormAction(), '?ItemType='.rawurlencode($this->request->getVar('ItemType'))));
                }else {
                    user_error('You must have the GridField Component "AddNewItemTypeButton" in your GridField config', E_USER_ERROR);
                }
            }else if(!$this->record->exists()) {
                user_error('No item type selected', E_USER_ERROR);
            }
        }
        
        return $form;
    }
}
?>
