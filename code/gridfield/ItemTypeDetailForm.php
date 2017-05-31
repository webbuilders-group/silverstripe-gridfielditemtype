<?php
class ItemTypeDetailForm extends GridFieldDetailForm
{
    /**
     * Handles the request for an item
     * @param {GridField} $gridField Grid Field reference
     * @param {SS_HTTPRequest} $request HTTP Request
     * @return {mixed} Returns the result of handleRequest on the item request handler
     */
    public function handleItem($gridField, $request)
    {
        $controller=$gridField->getForm()->Controller();
        
        if (is_numeric($request->param('ID'))) {
            $record=$gridField->getList()->byId($request->param("ID"));
        } elseif ($request->getVar('ItemType')) {
            if ($request->getVar('ItemType')==$gridField->getModelClass() || is_subclass_of($request->getVar('ItemType'), $gridField->getModelClass())) {
                $record=Object::create($request->getVar('ItemType'));
            } else {
                user_error('Class '.$request->getVar('ItemType').' is not a sub class of '.$gridField->getModelClass(), E_USER_ERROR);
            }
        } else {
            user_error('No item type selected', E_USER_ERROR);
        }
        
        $class=$this->getItemRequestClass();
        
        $handler=Object::create($class, $gridField, $this, $record, $controller, $this->name);
        $handler->setTemplate($this->template);
        
        return $handler->handleRequest($request, DataModel::inst());
    }
}

class ItemTypeDetailForm_ItemRequest extends GridFieldDetailForm_ItemRequest
{
    private static $allowed_actions=array(
                                            'edit'
                                        );
    
    /**
     * Handles the new/edit screen
     * @param {SS_HTTPRequest} $request HTTP Request
     * @return {string} HTML to be rendered
     */
    public function edit($request)
    {
        $controller=$this->getToplevelController();
        $form=$this->ItemEditForm($this->gridField, $request);
        
        if ($this->record->ID==0) {
            if ($request->getVar('ItemType')) {
                if ($addButton=$this->gridField->getConfig()->getComponentByType('AddNewItemTypeButton')) {
                    $values=$addButton->getRawDropdownValues();
                    
                    if (!array_key_exists($request->getVar('ItemType'), $values)) {
                        user_error('The item type "'.htmlentities($request->getVar('ItemType')).'" is not one of the available item types', E_USER_ERROR);
                    }
                    
                    $form->setFormAction(Controller::join_links($form->FormAction(), '?ItemType='.$request->getVar('ItemType')));
                } else {
                    user_error('You must have the GridField Component "AddNewItemTypeButton" in your GridField config', E_USER_ERROR);
                }
            }
        }
        
        
        $return=$this->customise(array(
                                        'Backlink'=>($controller->hasMethod('Backlink') ? $controller->Backlink():$controller->Link()),
                                        'ItemEditForm'=>$form,
                                    ))->renderWith($this->template);
        
        if ($request->isAjax()) {
            return $return;
        } else {
            // If not requested by ajax, we need to render it within the controller context+template
            return $controller->customise(array(
                                                // TODO CMS coupling
                                                'Content' => $return,
                                            ));
        }
    }
}
