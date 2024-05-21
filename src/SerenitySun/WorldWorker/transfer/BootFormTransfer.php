<?php

namespace SerenitySun\WorldWorker\transfer;

use SerenitySun\WorldWorker\form\lib\CustomFormResponse;
use SerenitySun\WorldWorker\form\lib\menu\Button;

class BootFormTransfer implements TransferInterface
{
    private ?string $worldName;

    /**
     * @param string|null $worldName
     */
    public function __construct(?string $worldName)
    {
        $this->worldName = $worldName;
    }


    /**
     * @return string|null
     */
    public function getWorldName(): ?string
    {
        return $this->worldName;
    }


    /**
     * @param bool|Button|CustomFormResponse $response
     * @return self
     */
    public static function transfer(bool|Button|CustomFormResponse $response): TransferInterface
    {
        if (empty($response->getValues())) {
            return new self(null);
        }

        return new self($response->getDropdown()->getSelectedOption());
    }
}
