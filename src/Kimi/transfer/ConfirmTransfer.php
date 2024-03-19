<?php

namespace Kimi\transfer;

use Kimi\form\lib\CustomFormResponse;
use Kimi\form\lib\menu\Button;
use Kimi\exception\FormTransferException;

class ConfirmTransfer implements TransferInterface
{
    private bool $value;

    /**
     * @param bool $value
     */
    public function __construct(bool $value)
    {
        $this->value = $value;
    }


    /**
     * @return bool
     */
    public function getValue(): bool
    {
        return $this->value;
    }


    /**
     * @param bool|Button|CustomFormResponse $response
     * @return TransferInterface
     */
    public static function transfer(bool|Button|CustomFormResponse $response): TransferInterface
    {
        if (is_bool($response)) {
            return new self($response);
        }

        throw new FormTransferException('response must be of type bool' . gettype($response) . 'caught');
    }
}
