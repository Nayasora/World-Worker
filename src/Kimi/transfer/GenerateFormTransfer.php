<?php

namespace Kimi\transfer;

use Kimi\form\lib\CustomFormResponse;
use Kimi\form\lib\menu\Button;

class GenerateFormTransfer extends ValidationFormTransfer implements TransferInterface
{
    private string $worldName;
    private string $worldType;
    private int    $worldDifficulty;
    private bool   $autoSave;
    private bool   $teleport;
    private int    $time;

    /**
     * @param string $worldName
     * @param string $worldType
     * @param int $worldDifficulty
     * @param bool $autoSave
     * @param bool $teleport
     * @param int $time
     * @param array|null $warnings
     */
    public function __construct(
        string $worldName,
        string $worldType,
        int    $worldDifficulty,
        bool   $autoSave,
        bool   $teleport,
        int    $time,
        array  $warnings = null
    ) {
        parent::__construct($warnings);
        $this->worldName        = $worldName;
        $this->worldType        = $worldType;
        $this->worldDifficulty  = $worldDifficulty;
        $this->autoSave         = $autoSave;
        $this->teleport         = $teleport;
        $this->time				= $time;
    }


    /**
     * @return string
     */
    public function getWorldName(): string
    {
        return $this->worldName;
    }


    /**
     * @return string
     */
    public function getWorldType(): string
    {
        return $this->worldType;
    }


    /**
     * @return bool
     */
    public function getTeleport(): bool
    {
        return $this->teleport;
    }


    /**
     * @return bool
     */
    public function getAutoSave(): bool
    {
        return  $this->autoSave;
    }


    /**
     * @return int
     */
    public function getWorldDifficulty(): int
    {
        return $this->worldDifficulty;
    }


    /**
     * @return int
     */
    public function getTime(): int
    {
        return $this->time;
    }


    /**
     * @param bool|Button|CustomFormResponse $response
     * @return self
     */
    public static function transfer(bool|Button|CustomFormResponse $response): self
    {
        $worldName       = $response->getInput()->getValue();
        $worldType       = $response->getDropdown()->getSelectedOption();
        $worldDifficulty = $response->getDropdown()->getValue();
        $autoSave        = $response->getToggle()->getValue();
        $teleport        = $response->getToggle()->getValue();
        $time 			 = $response->getSlider()->getValue();
        $warnings = null;

        if (empty($worldName)) {
            $warnings[] = 'world name must be not empty';
        }

        if (preg_match('/^\s*$/', $worldName)) {
            $warnings[] = 'world name is incorrect';
        }

        return new self(
            $worldName,
            $worldType,
            $worldDifficulty,
            $autoSave,
            $teleport,
            $time,
            $warnings
        );
    }
}
