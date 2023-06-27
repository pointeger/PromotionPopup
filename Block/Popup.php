<?php

declare(strict_types=1);

namespace Pointeger\PromotionPopup\Block;

use Magento\Framework\UrlInterface;
use Magento\Framework\View\Element\Template;
use Magento\Store\Model\ScopeInterface;

class Popup extends Template
{
    const URL_TYPE_MEDIA = 'media';
    const POPUP_FILE_FOLDER = 'promotion/post';
    /**
     * @var \Magento\Framework\App\Config\ScopeConfigInterface
     */
    private $scopeConfig;
    /**
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    private $storeManager;
    /**
     * @var \Magento\Framework\Stdlib\CookieManagerInterface
     */
    private $cookieManager;
    /**
     * @var \Magento\Framework\Stdlib\Cookie\CookieMetadataFactory
     */
    private $cookieMetadataFactory;

    /**
     * @param Template\Context $context
     * @param \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig
     * @param \Magento\Store\Model\StoreManagerInterface $storeManager
     * @param \Magento\Framework\Stdlib\CookieManagerInterface $cookieManager
     * @param \Magento\Framework\Stdlib\Cookie\CookieMetadataFactory $cookieMetadataFactory
     * @param array $data
     */
    public function __construct(
        Template\Context $context,
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Framework\Stdlib\CookieManagerInterface $cookieManager,
        \Magento\Framework\Stdlib\Cookie\CookieMetadataFactory $cookieMetadataFactory,
        array $data = []
    ) {
        $this->scopeConfig = $scopeConfig;
        $this->storeManager = $storeManager;
        $this->cookieManager = $cookieManager;
        $this->cookieMetadataFactory = $cookieMetadataFactory;;
        parent::__construct($context, $data);
    }

    /**
     * @return string
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getFileUrl()
    {
        $mergedPopFileUrl = '';
        $enable = $this->scopeConfig->getValue("promotion/general/enable", ScopeInterface::SCOPE_STORE);
        if ($enable) {
            $popup = $this->scopeConfig->getValue("promotion/general/popup_file", ScopeInterface::SCOPE_STORE);
            if ($popup) {
                $mergedPopFilePath = self::POPUP_FILE_FOLDER . '/' . $popup;
                $mergedPopFileUrl = $this->storeManager->getStore()->getBaseUrl(
                        UrlInterface::URL_TYPE_MEDIA
                    ) . $mergedPopFilePath;
                return $mergedPopFileUrl;
            }
        }
        return $mergedPopFileUrl;
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
