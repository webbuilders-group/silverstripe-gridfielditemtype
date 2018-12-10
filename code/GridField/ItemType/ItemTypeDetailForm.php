<?php
namespace WebbuildersGroup\ItemType;

use SilverStripe\Forms\GridField\GridFieldDetailForm;
use SilverStripe\ORM\DataObject;
use SilverStripe\ORM\DataList;
use SilverStripe\Core\Injector\Injector;


class ItemTypeDetailForm extends GridFieldDetailForm {
    /**
     * Handles the request for an item
     * @param {GridField} $gridField Grid Field reference
     * @param {SS_HTTPRequest} $request HTTP Request
     * @return {mixed} Returns the result of handleRequest on the item request handler
     */
    public function handleItem($gridField, $request) {
        $controller=$gridField->getForm()->getController();
        
        if(is_numeric($request->param('ID'))) {
            $record=$gridField->getList()->byId($request->param("ID"));
        }else if($request->getVar('ItemType')) {
            if($request->getVar('ItemType')==$gridField->getModelClass() || is_subclass_of($request->getVar('ItemType'), $gridField->getModelClass())) {
                $itemType = $request->getVar('ItemType');
                $record= Injector::inst()->get($itemType);
            }else {
                user_error('Class '.$request->getVar('ItemType').' is not a sub class of '.$gridField->getModelClass(), E_USER_ERROR);
            }
        }else {
            user_error('No item type selected', E_USER_ERROR);
        }
        
        $class=$this->getItemRequestClass();
        
        $handler= $class::create($gridField, $this, $record, $controller, $this->name);
        $handler->setTemplate($this->template);

        // if no validator has been set on the GridField and the record has a
        // CMS validator, use that.
        if(!$this->getValidator() && (method_exists($record, 'getCMSValidator') || $record instanceof DataObject && $record->hasMethod('getCMSValidator'))) {
                $this->setValidator($record->getCMSValidator());
        }
        
        return $handler->handleRequest($request);
    }
}