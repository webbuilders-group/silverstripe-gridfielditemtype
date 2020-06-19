GridFieldItemType
=================

Adds type/class picking functionality to SilverStripe 4's GridField.

## Maintainer Contact
* Ed Chipman ([UndefinedOffset](https://github.com/UndefinedOffset))

## Requirements
* SilverStripe 4.5+


## Installation
* Download the module from here https://github.com/webbuilders-group/silverstripe-gridfielditemtype/archive/master.zip
* Extract the downloaded archive into your site root so that the destination folder is called GridFieldItemType, opening the extracted folder should contain _config.php in the root along with other files/folders
* Run dev/build?flush=all to regenerate the manifest
* Upon entering the cms and using GridFieldItemType components for the first time you make need to add ?flush=all to the end of the address to force the templates to regenerate


## Usage
If you are working with one of the pre-configured GridFieldConfigs you must first remove the default GridFieldDetailForm and GridFieldAddNewButton components replacing them with ItemTypeDetailForm and AddNewItemTypeButton respectively
```php
use WebbuildersGroup\GridField\ItemType\AddNewItemTypeButton;
use SilverStripe\Forms\GridField\GridFieldConfig_RecordEditor;

$config=GridFieldConfig_RecordEditor::create(10);
$config->removeComponentsByType('GridFieldAddNewButton');
$config->removeComponentsByType('GridFieldDetailForm');
$config->addComponent(new AddNewItemTypeButton($yourOptionsMap, 'buttons-before-left', 'empty string', 'default'));
$config->addComponent(new ItemTypeDetailForm());
```

If you are using the base GridField config you need to add both the AddNewItemTypeButton and ItemTypeDetailForm to your config
```php
use WebbuildersGroup\GridField\ItemType\AddNewItemTypeButton;
use SilverStripe\Forms\GridField\GridFieldConfig_RecordEditor;

$config=GridFieldConfig_Base::create(10);
$config->addComponent(new AddNewItemTypeButton($yourOptionsMap, 'buttons-before-left'));
$config->addComponent(new ItemTypeDetailForm());
```

If you are managing a versioned object you must override the item request class using:
```php
use WebbuildersGroup\GridField\ItemType\VersionedItemTypeDetailForm_ItemRequest;

$detailForm->setItemRequestClass(VersionedItemTypeDetailForm_ItemRequest::class);
```

Note: All options in the type dropdown must be decendents of the model class.
