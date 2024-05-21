<?php

namespace SerenitySun\WorldWorker\transfer;

use SerenitySun\WorldWorker\form\lib\CustomFormResponse;
use Kimi\WorldWorker\form\lib\menu\Button;

class GenerateFormTransfer extends ValidationFormTransfer implements TransferInterface
{
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
        private readonly string $worldName,
        private readonly string $worldType,
        private readonly int    $worldDifficulty,
        private readonly bool   $autoSave,
        private readonly bool   $teleport,
        private readonly int    $time,
        private readonly bool   $stopTime,
        readonly ?array         $warnings
    ) {
        parent::__construct($warnings);
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
     * @return bool
     */
    public function getStopTime(): bool
    {
        return $this->stopTime;
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
        $stopTime        = $response->getToggle()->getValue();

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
            $stopTime,
            $warnings
        );
    }
}
