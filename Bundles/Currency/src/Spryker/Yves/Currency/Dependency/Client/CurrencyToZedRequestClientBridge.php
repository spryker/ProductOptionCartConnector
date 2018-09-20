<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Yves\Currency\Dependency\Client;

class CurrencyToZedRequestClientBridge implements CurrencyToZedRequestClientInterface
{
    /**
     * @var \Spryker\Client\ZedRequest\ZedRequestClientInterface
     */
    protected $zedRequestClient;

    /**
     * @param \Spryker\Client\ZedRequest\ZedRequestClientInterface $zedRequestClient
     */
    public function __construct($zedRequestClient)
    {
        $this->zedRequestClient = $zedRequestClient;
    }

    /**
     * @return \Generated\Shared\Transfer\MessageTransfer[]
     */
    public function getLastResponseErrorMessages()
    {
        return $this->zedRequestClient->getLastResponseErrorMessages();
    }

    /**
     * @return \Generated\Shared\Transfer\MessageTransfer[]
     */
    public function getLastResponseSuccessMessages()
    {
        return $this->zedRequestClient->getLastResponseSuccessMessages();
    }

    /**
     * @return \Generated\Shared\Transfer\MessageTransfer[]
     */
    public function getAllResponsesErrorMessages(): array
    {
        return $this->zedRequestClient->getAllResponsesErrorMessages();
    }

    /**
     * @return \Generated\Shared\Transfer\MessageTransfer[]
     */
    public function getAllResponsesSuccessMessages(): array
    {
        return $this->zedRequestClient->getAllResponsesSuccessMessages();
    }
}
