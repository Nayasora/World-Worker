<?php

namespace SerenitySun\WorldWorker\transfer;

use SerenitySun\WorldWorker\form\lib\CustomFormResponse;
use SerenitySun\WorldWorker\form\lib\menu\Button;

class MainFormTransfer implements TransferInterface
{
    private string $operationName;

    /**
     * @param string $operationName
     */
    public function __construct(string $operationName)
    {
        $this->operationName = $operationName;
    }


    /**
     * @return string
     */
    public function getOperationName(): string
    {
        return $this->operationName;
    }


    /**
     * @param bool|Button|CustomFormResponse $response
     * @return self
     */
    public static function transfer(bool|Button|CustomFormResponse $response): self
    {
        $operationName = $response->text;

        return new self($operationName);
    }
}
