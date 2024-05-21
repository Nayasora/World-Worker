<?php

namespace SerenitySun\WorldWorker\transfer;

class ValidationFormTransfer
{
    public function __construct(private readonly ?array $warning = null){}

    /**
     * @return array|null
     */
    public function getValidationWarnings(): ?array
    {
        return $this->warning;
    }
}
