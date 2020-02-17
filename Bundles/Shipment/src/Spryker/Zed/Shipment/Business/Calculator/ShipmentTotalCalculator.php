<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Shipment\Business\Calculator;

use ArrayObject;
use Generated\Shared\Transfer\CalculableObjectTransfer;
use Spryker\Shared\Shipment\ShipmentConfig as SharedShipmentConfig;

class ShipmentTotalCalculator implements ShipmentTotalCalculatorInterface
{
    /**
     * @param \Generated\Shared\Transfer\CalculableObjectTransfer $calculableObjectTransfer
     *
     * @return void
     */
    public function calculateShipmentTotal(CalculableObjectTransfer $calculableObjectTransfer): void
    {
        $calculableObjectTransfer->requireTotals();

        $shipmentTotal = $this->getShipmentTotalSumPrice($calculableObjectTransfer->getExpenses());

        $calculableObjectTransfer
            ->getTotals()
            ->setShipmentTotal($shipmentTotal);
    }

    /**
     * @param \ArrayObject|\Generated\Shared\Transfer\ExpenseTransfer[] $expenseTransfers
     *
     * @return int
     */
    protected function getShipmentTotalSumPrice(ArrayObject $expenseTransfers): int
    {
        $shipmentTotal = 0;

        foreach ($expenseTransfers as $expenseTransfer) {
            if ($expenseTransfer->getType() !== SharedShipmentConfig::SHIPMENT_EXPENSE_TYPE) {
                continue;
            }

            $shipmentTotal += $expenseTransfer->getSumPrice();
        }

        return $shipmentTotal;
    }
}
