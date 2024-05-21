<?php

namespace SerenitySun\WorldWorker\transfer;

use SerenitySun\WorldWorker\form\lib\CustomFormResponse;
use SerenitySun\WorldWorker\form\lib\menu\Button;

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
