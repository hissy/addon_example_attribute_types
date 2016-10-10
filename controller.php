<?php
namespace Concrete\Package\ExampleAttributeTypes;

use Concrete\Core\Attribute\AttributeKeyInterface;
use Concrete\Core\Attribute\Category\CategoryInterface;
use Concrete\Core\Backup\ContentImporter;
use Concrete\Core\Entity\Attribute\Category;
use Concrete\Core\Entity\Attribute\Type;
use Concrete\Core\Package\ItemCategory\AttributeType;
use Concrete\Core\Package\Package;

class Controller extends Package
{
    /**
     * @var string Package handle.
     */
    protected $pkgHandle = 'example_attribute_types';

    /**
     * @var string Required concrete5 version.
     */
    protected $appVersionRequired = '8.0.0b6';

    /**
     * @var string Package version.
     */
    protected $pkgVersion = '0.1.2';

    /**
     * @var bool Remove \Src from package namespace.
     */
    protected $pkgAutoloaderMapCoreExtensions = true;

    public function getPackageDescription()
    {
        return t('An example package to install custom attribute types for version 8.');
    }

    public function getPackageName()
    {
        return t('Install Attribute Types Example Package');
    }

    public function install()
    {
        $pkg = parent::install();
        $ci = new ContentImporter();
        $ci->importContentFile($pkg->getPackagePath() . '/config/install.xml');
    }

    public function uninstall()
    {
        /** @var \Concrete\Core\Entity\Package $pkg */
        $pkg = $this->getPackageEntity();

        $packageAttributeTypeIDs = [];
        /** @var AttributeType $atDriver */
        $atDriver = $this->app->make('Concrete\Core\Package\ItemCategory\AttributeType');
        if ($atDriver->hasItems($pkg)) {
            /** @var Type[] $items */
            $items = $atDriver->getItems($pkg);
            foreach ($items as $item) {
                $packageAttributeTypeIDs[] = $item->getAttributeTypeID();
            }
        }

        /** @var Category[] $categories */
        $categories = \Concrete\Core\Attribute\Key\Category::getList();
        foreach ($categories as $category) {
            /** @var CategoryInterface $categoryController */
            $categoryController = $category->getController();
            /** @var AttributeKeyInterface[] $keys */
            $keys = \Concrete\Core\Attribute\Key\Key::getList($categoryController);
            foreach ($keys as $key) {
                /** @var Type $at */
                $at = $key->getAttributeType();
                if (in_array($at->getAttributeTypeID(), $packageAttributeTypeIDs)) {
                    $categoryController->deleteKey($key);
                }
            }
        }

        parent::uninstall();
    }
}
