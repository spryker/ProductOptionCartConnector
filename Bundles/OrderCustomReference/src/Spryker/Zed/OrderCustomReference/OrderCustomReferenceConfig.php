<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\OrderCustomReference;

use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Zed\Kernel\AbstractBundleConfig;

/**
 * @method \Spryker\Shared\OrderCustomReference\OrderCustomReferenceConfig getSharedConfig()
 */
class OrderCustomReferenceConfig extends AbstractBundleConfig
{
    /**
     * @return string[]
     */
    public function getOrderCustomReferenceQuoteFieldsAllowedForSaving(): array
    {
        return [
            QuoteTransfer::ORDER_CUSTOM_REFERENCE,
        ];
    }

    /**
     * @return int
     */
    public function getOrderCustomReferenceMaxLength(): int
    {
        return $this->getSharedConfig()->getOrderCustomReferenceMaxLength();
    }
}
