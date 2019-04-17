<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ContentGui\Communication\Mapper;

interface ContentMapperInterface
{
    /**
     * @param array $contentTypes
     *
     * @return array
     */
    public function mapEnabledContentTypesForEditor(array $contentTypes): array;
}
