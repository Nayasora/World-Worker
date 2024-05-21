<?php

namespace SerenitySun\WorldWorker\transfer;

use SerenitySun\WorldWorker\form\lib\CustomFormResponse;
use SerenitySun\WorldWorker\form\lib\menu\Button;

class RenameFormTransfer extends ValidationFormTransfer implements TransferInterface
{
    private string $newName;
    private string $lastName;

    /**
     * @param string $newName
     * @param string $lastName
     * @param array|null $warnings
     */
    public function __construct(string $newName, string $lastName, array $warnings = null)
    {
        parent::__construct($warnings);
        $this->newName  = $newName;
        $this->lastName = $lastName;
    }


    /**
     * @return string
     */
    public function getNewName(): string
    {
        return $this->newName;
    }


    /**
     * @return string
     */
    public function getLastName(): string
    {
        return $this->lastName;
    }


    /**
     * @param bool|Button|CustomFormResponse $response
     * @return TransferInterface
     */
    public static function transfer(bool|Button|CustomFormResponse $response): TransferInterface
    {
        $newName  = $response->getInput()->getValue();
        $lastName = $response->getDropdown()->getSelectedOption();

        $warnings = null;

        if (preg_match('[^\s*$]', $newName)) {
            $warnings[] = "please insert correct name";
        }

        return new self($newName, $lastName, $warnings);
    }
}
