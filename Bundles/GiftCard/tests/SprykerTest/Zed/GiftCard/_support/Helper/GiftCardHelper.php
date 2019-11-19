<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\GiftCard\Helper;

use Codeception\Module;
use Generated\Shared\DataBuilder\GiftCardBuilder;
use Generated\Shared\Transfer\GiftCardTransfer;
use SprykerTest\Shared\Testify\Helper\LocatorHelperTrait;

class GiftCardHelper extends Module
{
    use LocatorHelperTrait;

    /**
     * @param array $seedData
     *
     * @return \Generated\Shared\Transfer\GiftCardTransfer
     */
    public function haveGiftCard(array $seedData = []): GiftCardTransfer
    {
        $giftCardTransfer = (new GiftCardBuilder($seedData))->build();
        $giftCardTransfer->setIdGiftCard(null);

        $this->getLocator()->giftCard()->facade()->create($giftCardTransfer);

        return $giftCardTransfer;
    }
}
