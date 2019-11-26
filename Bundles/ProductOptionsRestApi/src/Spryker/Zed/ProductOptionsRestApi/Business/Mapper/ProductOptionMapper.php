<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOptionsRestApi\Business\Mapper;

use Generated\Shared\Transfer\CartItemRequestTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\PersistentCartChangeTransfer;
use Generated\Shared\Transfer\ProductOptionTransfer;

class ProductOptionMapper implements ProductOptionMapperInterface
{
    /**
     * @param \Generated\Shared\Transfer\CartItemRequestTransfer $cartItemRequestTransfer
     * @param \Generated\Shared\Transfer\PersistentCartChangeTransfer $persistentCartChangeTransfer
     *
     * @return \Generated\Shared\Transfer\PersistentCartChangeTransfer
     */
    public function mapCartItemRequestTransferToPersistentCartChangeTransfer(
        CartItemRequestTransfer $cartItemRequestTransfer,
        PersistentCartChangeTransfer $persistentCartChangeTransfer
    ): PersistentCartChangeTransfer {
        foreach ($persistentCartChangeTransfer->getItems() as $itemTransfer) {
            if ($itemTransfer->getSku() === $cartItemRequestTransfer->getSku()) {
                $this->mapCartItemRequestTransferToItemTransfer($cartItemRequestTransfer, $itemTransfer);

                break;
            }
        }

        return $persistentCartChangeTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\CartItemRequestTransfer $cartItemRequestTransfer
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     *
     * @return \Generated\Shared\Transfer\ItemTransfer
     */
    protected function mapCartItemRequestTransferToItemTransfer(
        CartItemRequestTransfer $cartItemRequestTransfer,
        ItemTransfer $itemTransfer
    ): ItemTransfer {
        foreach ($cartItemRequestTransfer->getProductOptions() as $cartItemRequestProductOptionTransfer) {
            $productOptionTransfer = (new ProductOptionTransfer())
                ->setIdProductOptionValue($cartItemRequestProductOptionTransfer->getIdProductOption());
            $itemTransfer->addProductOption($productOptionTransfer);
        }

        return $itemTransfer;
    }
}
