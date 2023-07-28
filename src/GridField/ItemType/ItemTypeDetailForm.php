<?php
namespace WebbuildersGroup\GridField\ItemType;

use SilverStripe\Control\HTTPRequest;
use SilverStripe\Core\Injector\Injector;
use SilverStripe\Forms\GridField\GridField;
use SilverStripe\Forms\GridField\GridFieldDetailForm;
use SilverStripe\ORM\DataObject;

class ItemTypeDetailForm extends GridFieldDetailForm
{
    protected $itemRequestClass = ItemTypeDetailForm_ItemRequest::class;

    /**
     * @param GridField $gridField
     * @param HTTPRequest $request
     * @return DataObject|null
     */
    protected function getRecordFromRequest(GridField $gridField, HTTPRequest $request): ?DataObject
    {
        /** @var DataObject $record */
        if (is_numeric($request->param('ID'))) {
            /** @var Filterable $dataList */
            $dataList = $gridField->getList();
            $record = $dataList->byID($request->param('ID'));
        } else if ($request->getVar('ItemType')) {
            if ($request->getVar('ItemType') == $gridField->getModelClass() || is_subclass_of($request->getVar('ItemType'), $gridField->getModelClass())) {
                $itemType = $request->getVar('ItemType');
                $record = Injector::inst()->get($itemType);
            } else {
                user_error('Class ' . $request->getVar('ItemType') . ' is not a sub class of ' . $gridField->getModelClass(), E_USER_ERROR);
            }
        } else {
            user_error('No item type selected', E_USER_ERROR);
        }

        return $record;
    }
}
