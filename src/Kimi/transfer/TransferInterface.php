<?php

namespace Kimi\transfer;

use Kimi\form\lib\CustomFormResponse;
use Kimi\form\lib\menu\Button;

interface TransferInterface
{
    /**
     * Transfers response to self object with validation for easy use.
     *
     * @param bool|Button|CustomFormResponse $response
     * @return static
     */
    public static function transfer(bool|Button|CustomFormResponse $response): self;
}
