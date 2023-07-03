<?php

declare(strict_types=1);

namespace Pointeger\PromotionPopup\Block;

use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\Stdlib\Cookie\CookieMetadataFactory;
use Magento\Framework\Stdlib\CookieManagerInterface;
use Magento\Framework\View\Element\Template;
use Magento\Store\Model\ScopeInterface;

class Popup extends Template
{
    /**
     * @var ScopeConfigInterface
     */
    private $scopeConfig;
    /**
     * @var CookieManagerInterface
     */
    private $cookieManager;
    /**
     * @var CookieMetadataFactory
     */
    private $cookieMetadataFactory;

    /**
     * @param Template\Context $context
     * @param ScopeConfigInterface $scopeConfig
     * @param CookieManagerInterface $cookieManager
     * @param CookieMetadataFactory $cookieMetadataFactory
     * @param array $data
     */
    public function __construct(
        Template\Context $context,
        ScopeConfigInterface $scopeConfig,
        CookieManagerInterface $cookieManager,
        CookieMetadataFactory $cookieMetadataFactory,
        array $data = []
    ) {
        $this->scopeConfig = $scopeConfig;
        $this->cookieManager = $cookieManager;
        $this->cookieMetadataFactory = $cookieMetadataFactory;;
        parent::__construct($context, $data);
    }

    /**
     * @return bool
     */
    public function CheckPopupConfig()
    {
        $enable = $this->scopeConfig->getValue("promotion/general/enable", ScopeInterface::SCOPE_STORE);
        if ($enable) {
            return true;
        }
        return false;
    }

    /**
     * @return string|null
     */
    public function getPromotionCookie()
    {
        return $this->cookieManager->getCookie(
            'show_promotion_popup'
        );
    }

    /**
     * @throws \Magento\Framework\Exception\InputException
     * @throws \Magento\Framework\Stdlib\Cookie\FailureToSendException
     */
    public function setPromotionCookie()
    {
        if ($this->cookieManager->getCookie('show_promotion_popup')) {
            $metadata = $this->cookieMetadataFactory->createPublicCookieMetadata();
            $metadata->setPath('/');

            $this->cookieManager->deleteCookie(
                'show_promotion_popup',
                $metadata
            );
        }
    }

    /**
     * @param $cmsBlockIdentifier
     * @return string
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function renderCmsBlock($cmsBlockIdentifier)
    {
        $this->getCmsBlocks();
        try {
            return $this->getLayout()
                ->createBlock('Magento\Cms\Block\Block')
                ->setBlockId($cmsBlockIdentifier)
                ->toHtml();
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }
}
