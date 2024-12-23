<?php

namespace Altados\Announcements\Helper;

use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Store\Model\ScopeInterface;

class Data extends AbstractHelper
{
    const XML_PATH_ENABLED = 'announcements/general/enabled';

    /**
     * Check if announcements are enabled
     *
     * @return bool
     */
    public function isAnnouncementsEnabled()
    {
        return $this->scopeConfig->isSetFlag(self::XML_PATH_ENABLED, ScopeInterface::SCOPE_STORE);
    }
}
