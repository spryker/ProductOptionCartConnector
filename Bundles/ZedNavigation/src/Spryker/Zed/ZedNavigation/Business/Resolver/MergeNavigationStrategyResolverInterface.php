<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ZedNavigation\Business\Resolver;

use Spryker\Zed\ZedNavigation\Business\Strategy\NavigationMergeStrategyInterface;

interface MergeNavigationStrategyResolverInterface
{
    /**
     * @param string $mergeStrategy
     *
     * @return \Spryker\Zed\ZedNavigation\Business\Strategy\NavigationMergeStrategyInterface|null
     */
    public function resolve(string $mergeStrategy): ?NavigationMergeStrategyInterface;
}
