<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantOms\Business\MerchantOmsProcess;

use Generated\Shared\Transfer\MerchantOmsProcessCriteriaFilterTransfer;
use Generated\Shared\Transfer\MerchantOmsProcessTransfer;
use Spryker\Zed\MerchantOms\MerchantOmsConfig;
use Spryker\Zed\MerchantOms\Persistence\MerchantOmsRepositoryInterface;

class MerchantOmsProcessReader implements MerchantOmsProcessReaderInterface
{
    /**
     * @var \Spryker\Zed\MerchantOms\Persistence\MerchantOmsRepositoryInterface
     */
    protected $merchantOmsRepository;

    /**
     * @var \Spryker\Zed\MerchantOms\MerchantOmsConfig
     */
    protected $merchantOmsConfig;

    /**
     * @param \Spryker\Zed\MerchantOms\Persistence\MerchantOmsRepositoryInterface $merchantOmsRepository
     * @param \Spryker\Zed\MerchantOms\MerchantOmsConfig $merchantOmsConfig
     */
    public function __construct(
        MerchantOmsRepositoryInterface $merchantOmsRepository,
        MerchantOmsConfig $merchantOmsConfig
    ) {
        $this->merchantOmsRepository = $merchantOmsRepository;
        $this->merchantOmsConfig = $merchantOmsConfig;
    }

    /**
     * @inheritDoc
     */
    public function getMerchantOmsProcess(MerchantOmsProcessCriteriaFilterTransfer $merchantOmsProcessCriteriaFilterTransfer): MerchantOmsProcessTransfer
    {
        $merchantOmsProcessTransfer = $this->merchantOmsRepository->findMerchantOmsProcess($merchantOmsProcessCriteriaFilterTransfer);

        if (!$merchantOmsProcessTransfer) {
            $merchantOmsProcessTransfer = (new MerchantOmsProcessTransfer())
                ->setProcessName($this->merchantOmsConfig->getMerchantOmsDefaultProcessName());
        }

        return $merchantOmsProcessTransfer;
    }
}
