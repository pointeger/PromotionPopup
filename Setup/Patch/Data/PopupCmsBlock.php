<?php

declare(strict_types=1);

namespace Pointeger\PromotionPopup\Setup\Patch\Data;

use Magento\Cms\Model\BlockFactory;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Framework\Setup\Patch\DataPatchInterface;
use Magento\Store\Model\Store;

/**
 * Class PopupCmsBlock
 * @package Pointeger\PromotionPopup\Setup\Patch\Data
 */
class PopupCmsBlock implements DataPatchInterface
{
    const CUSTOMER_LOGIN_CMS_BLOCK_IDENTIFIER = 'pointeger-popup-cmsblock';

    /**
     * @var ModuleDataSetupInterface
     */
    private $moduleDataSetup;
    /**
     * @var BlockFactory
     */
    private $blockFactory;

    /**
     * @param ModuleDataSetupInterface $moduleDataSetup
     * @param BlockFactory $blockFactory
     */
    public function __construct(
        ModuleDataSetupInterface $moduleDataSetup,
        BlockFactory             $blockFactory
    )
    {
        $this->moduleDataSetup = $moduleDataSetup;
        $this->blockFactory = $blockFactory;
    }

    /**
     * @return array|string[]
     */
    public static function getDependencies()
    {
        return [];
    }

    /**
     * @return array|string[]
     */
    public function getAliases()
    {
        return [];
    }

    /**
     * @return PopupCmsBlock|void
     */
    public function apply()
    {
        $this->moduleDataSetup->startSetup();
        $popupContent = '<div class="pointeger-popup">
        <h1>Add Content for your PopUp in Customer Login Popup CMS Block.</h1>
        </div>';
        $this->blockFactory->create()
            ->setTitle('Customer Login Popup')
            ->setIdentifier(self::CUSTOMER_LOGIN_CMS_BLOCK_IDENTIFIER)
            ->setIsActive(true)
            ->setContent($popupContent)
            ->setStores([Store::DEFAULT_STORE_ID])
            ->save();

        $this->moduleDataSetup->endSetup();
    }
}



