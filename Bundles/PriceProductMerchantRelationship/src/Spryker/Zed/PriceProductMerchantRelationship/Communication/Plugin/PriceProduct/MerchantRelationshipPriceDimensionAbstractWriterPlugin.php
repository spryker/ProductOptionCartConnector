<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProductMerchantRelationship\Communication\Plugin\PriceProduct;

use Generated\Shared\Transfer\PriceProductTransfer;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\PriceProduct\Dependency\Plugin\PriceDimensionAbstractSaverPluginInterface;

/**
 * @method \Spryker\Zed\PriceProductMerchantRelationship\Business\PriceProductMerchantRelationshipFacadeInterface getFacade()
 * @method \Spryker\Zed\PriceProductMerchantRelationship\Communication\PriceProductMerchantRelationshipCommunicationFactory getFactory()
 * @method \Spryker\Zed\PriceProductMerchantRelationship\PriceProductMerchantRelationshipConfig getConfig()
 */
class MerchantRelationshipPriceDimensionAbstractWriterPlugin extends AbstractPlugin implements PriceDimensionAbstractSaverPluginInterface
{
    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\PriceProductTransfer $priceProductTransfer
     *
     * @return \Generated\Shared\Transfer\PriceProductTransfer
     */
    public function savePrice(PriceProductTransfer $priceProductTransfer): PriceProductTransfer
    {
        return $this->getFacade()->savePriceProductMerchantRelationship($priceProductTransfer);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @return string
     */
    public function getDimensionName(): string
    {
        return $this->getConfig()->getPriceDimensionMerchantRelationship();
    }
}
