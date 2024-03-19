<?php

namespace Kimi\transfer;

class ValidationFormTransfer
{
    private ?array $warning;

    public function __construct(array $warning = null)
    {
        $this->warning = $warning;
    }


    /**
     * @return array|null
     */
    public function getValidationWarnings(): ?array
    {
        return $this->warning;
    }
}
