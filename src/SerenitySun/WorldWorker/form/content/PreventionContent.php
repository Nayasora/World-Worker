<?php

namespace SerenitySun\WorldWorker\form\content;

use SerenitySun\WorldWorker\form\lib\element\Label;
use UnexpectedValueException;

abstract class PreventionContent extends Content
{
    private array $preventionLabels;

    /**
     * @param string $prevention
     * @return void
     */
    public function setPrevention(string $prevention): void
    {
        $this->preventionLabels[] = new Label($prevention);
    }


    /**
     * @param array $preventions
     * @return void
     */
    public function setPreventions(array $preventions): void
    {
        foreach ($preventions as $prevention) {
            if (!is_string($prevention)) {
                throw new UnexpectedValueException('prevention must be of type string');
            }

            $this->setPrevention($prevention);
        }
    }


    /**
     * @return bool
     */
    public function hasPrevention(): bool
    {
        return !empty($this->preventionLabels);
    }


    /**
     * @return array
     */
    public function getPreventions(): array
    {
        return $this->preventionLabels;
    }
}
