<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductBundle\Plugin\Cart;

use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Client\Cart\Dependency\Plugin\QuoteItemFinderPluginInterface;

/**
 * @method \Spryker\Client\ProductBundle\ProductBundleFactory getFactory()
 */
class BundleProductQuoteItemFinderPlugin implements QuoteItemFinderPluginInterface
{
    /**
     * Specification:
     * - Find item in quote
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param string $sku
     * @param string|null $groupKey
     *
     * @return \Generated\Shared\Transfer\ItemTransfer|null
     */
    public function findItem(QuoteTransfer $quoteTransfer, $sku, $groupKey = null): ?ItemTransfer
    {
        return $this->getFactory()->createBundleProductQuoteItemFinder()->findItem($quoteTransfer, $sku, $groupKey);
    }
}
