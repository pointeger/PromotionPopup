<?php

declare(strict_types=1);

namespace Pointeger\PromotionPopup\Plugin;

use Magento\Backend\Block\Template\Context;
use Magento\Framework\Stdlib\Cookie\CookieMetadataFactory;
use Magento\Framework\Stdlib\CookieManagerInterface;

class LoginFlag
{
    /**
     * @var CookieManagerInterface
     */
    private $cookieManager;

    /**
     * @var CookieMetadataFactory
     */
    private $cookieMetadataFactory;

    /**
     *
     * LoginFlag constructor.
     * @param Context $context
     * @param CookieManagerInterface $cookieManager
     * @param CookieMetadataFactory $cookieMetadataFactory
     * @param array $data
     */
    public function __construct(
        Context $context,
        CookieManagerInterface $cookieManager,
        CookieMetadataFactory $cookieMetadataFactory,
        array $data = []
    ) {
        $this->cookieManager = $cookieManager;
        $this->cookieMetadataFactory = $cookieMetadataFactory;
    }

    /**
     * @param \Magento\Customer\Controller\Account\LoginPost $subject
     * @param $result
     * @return mixed
     * @throws \Magento\Framework\Exception\InputException
     * @throws \Magento\Framework\Stdlib\Cookie\CookieSizeLimitReachedException
     * @throws \Magento\Framework\Stdlib\Cookie\FailureToSendException
     */
    public function afterExecute(\Magento\Customer\Controller\Account\LoginPost $subject, $result)
    {
        $publicCookieMetadata = $this->cookieMetadataFactory->createPublicCookieMetadata();
        $publicCookieMetadata->setDurationOneYear();
        $publicCookieMetadata->setPath('/');
        $publicCookieMetadata->setHttpOnly(false);
        $this->cookieManager->setPublicCookie(
            'show_promotion_popup',
            'yes',
            $publicCookieMetadata
        );
        return $result;
    }
}
