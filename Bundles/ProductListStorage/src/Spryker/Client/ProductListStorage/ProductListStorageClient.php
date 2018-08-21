<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductListStorage;

use Generated\Shared\Transfer\ProductAbstractProductListStorageTransfer;
use Generated\Shared\Transfer\ProductConcreteProductListStorageTransfer;
use Spryker\Client\Kernel\AbstractClient;

/**
 * @method \Spryker\Client\ProductListStorage\ProductListStorageFactory getFactory()
 */
class ProductListStorageClient extends AbstractClient implements ProductListStorageClientInterface
{
    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param int $idProductAbstract
     *
     * @return \Generated\Shared\Transfer\ProductAbstractProductListStorageTransfer|null
     */
    public function findProductAbstractProductListStorage(int $idProductAbstract): ?ProductAbstractProductListStorageTransfer
    {
        return $this->getFactory()
            ->createProductListProductAbstractStorageReader()
            ->findProductAbstractProductListStorage($idProductAbstract);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param int $idProductConcrete
     *
     * @return \Generated\Shared\Transfer\ProductConcreteProductListStorageTransfer|null
     */
    public function findProductConcreteProductListStorage(int $idProductConcrete): ?ProductConcreteProductListStorageTransfer
    {
        return $this->getFactory()
            ->createProductListProductConcreteStorageReader()
            ->findProductConcreteProductListStorage($idProductConcrete);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param int $idProductAbstract
     *
     * @return bool
     */
    public function isProductAbstractRestricted(int $idProductAbstract): bool
    {
        return $this->getFactory()
            ->createProductAbstractRestrictionReader()
            ->isProductAbstractRestricted($idProductAbstract);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param int $idProductConcrete
     *
     * @return bool
     */
    public function isProductConcreteRestricted(int $idProductConcrete): bool
    {
        return $this->getFactory()
            ->createProductConcreteRestrictionReader()
            ->isProductConcreteRestricted($idProductConcrete);
    }
}
