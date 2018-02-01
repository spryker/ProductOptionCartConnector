<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductManagement;

use Spryker\Shared\ProductManagement\ProductManagementConstants;
use Spryker\Shared\ProductValidity\ProductValidityConstants;
use Spryker\Zed\Kernel\AbstractBundleConfig;

class ProductManagementConfig extends AbstractBundleConfig
{
    const PRODUCT_TYPE_BUNDLE = 'bundle';
    const PRODUCT_TYPE_REGULAR = 'regular';

    /**
     * @return string
     */
    public function getImageUrlPrefix()
    {
        return $this->get(ProductManagementConstants::BASE_URL_YVES);
    }

    /**
     * @return string
     */
    public function getHostYves()
    {
        return $this->get(ProductManagementConstants::BASE_URL_YVES);
    }

    /**
     * @return string
     */
    public function getValidityTimeFormat()
    {
        return ProductValidityConstants::VALIDITY_DATE_TIME_FORMAT;
    }
}
