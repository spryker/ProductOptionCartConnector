<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantProductOffer\Persistence;

use Generated\Shared\Transfer\MerchantProductOfferCriteriaFilterTransfer;
use Orm\Zed\ProductOffer\Persistence\SpyProductOfferQuery;
use Spryker\Zed\Kernel\Persistence\AbstractRepository;

/**
 * @method \Spryker\Zed\MerchantProductOffer\Persistence\MerchantProductOfferPersistenceFactory getFactory()
 */
class MerchantProductOfferRepository extends AbstractRepository implements MerchantProductOfferRepositoryInterface
{
    /**
     * @param \Generated\Shared\Transfer\MerchantProductOfferCriteriaFilterTransfer $merchantProductOfferCriteriaFilterTransfer
     * @param string[] $columnsToSelect
     *
     * @return mixed[]
     */
    public function getProductOfferData(
        MerchantProductOfferCriteriaFilterTransfer $merchantProductOfferCriteriaFilterTransfer,
        array $columnsToSelect
    ): array {
        $productOfferQuery = $this->applyFilters(
            $merchantProductOfferCriteriaFilterTransfer,
            $this->getFactory()->getProductOfferPropelQuery()
        );

        $productOfferQuery->select($columnsToSelect);

        return $productOfferQuery->find()->getData();
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantProductOfferCriteriaFilterTransfer $merchantProductOfferCriteriaFilterTransfer
     * @param \Orm\Zed\ProductOffer\Persistence\SpyProductOfferQuery $productOfferQuery
     *
     * @return \Orm\Zed\ProductOffer\Persistence\SpyProductOfferQuery
     */
    protected function applyFilters(
        MerchantProductOfferCriteriaFilterTransfer $merchantProductOfferCriteriaFilterTransfer,
        SpyProductOfferQuery $productOfferQuery
    ): SpyProductOfferQuery {
        if ($merchantProductOfferCriteriaFilterTransfer->getSkus()) {
            $productOfferQuery->filterByConcreteSku_In($merchantProductOfferCriteriaFilterTransfer->getSkus());
        }

        if ($merchantProductOfferCriteriaFilterTransfer->getIsActive() !== null) {
            $productOfferQuery->filterByIsActive($merchantProductOfferCriteriaFilterTransfer->getIsActive());
        }

        if ($merchantProductOfferCriteriaFilterTransfer->getMerchantReference()) {
            $productOfferQuery
                ->useSpyMerchantQuery()
                ->filterByMerchantReference($merchantProductOfferCriteriaFilterTransfer->getMerchantReference())
                ->endUse();
        }

        if ($merchantProductOfferCriteriaFilterTransfer->getProductOfferReferences()) {
            $productOfferQuery->filterByProductOfferReference_In($merchantProductOfferCriteriaFilterTransfer->getProductOfferReferences());
        }

        return $productOfferQuery;
    }
}
