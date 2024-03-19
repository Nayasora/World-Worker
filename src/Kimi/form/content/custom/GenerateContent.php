<?php

namespace Kimi\form\content\custom;

use Kimi\form\lib\element\Dropdown;
use Kimi\form\lib\element\Input;
use Kimi\form\lib\element\Slider;
use Kimi\form\lib\element\Toggle;
use Kimi\form\content\Content;
use Kimi\form\content\PreventionContent;
use Kimi\OperationsList;
use pocketmine\world\generator\GeneratorManager;
use pocketmine\world\World;

class GenerateContent extends PreventionContent implements CustomContentInterface
{
    public const WORLD_NAME_INPUT = 'set world name';
    public const GENERATION_TYPE = 'generation type';

    /**
     * @return array
     */
    public function getElements(): array
    {
        $body = [
            $this->getWorldNameInput(),
            $this->getGenerationTypesDropdown(),
            $this->getDifficultyDropdown(),
            $this->getAutoSaveToggle(),
            $this->getTeleportToggle(),
            $this->getTimeSlider()
        ];

        if ($this->hasPrevention()) {
            $this->appendComponent($body, $this->getPreventions());
        }

        return $body;
    }


    /**
     * @return string
     */
    public function getName(): string
    {
        return OperationsList::GENERATE_NAME;
    }


    /**
     * @return string
     */
    public function getType(): string
    {
        return Content::TYPE_CUSTOM;
    }


    /**
     * @return Input
     */
    public function getWorldNameInput(): Input
    {
        return new Input(self::WORLD_NAME_INPUT, 'new world');
    }


    /**
     * @return Dropdown
     */
    public function getGenerationTypesDropdown(): Dropdown
    {
        return new Dropdown(self::GENERATION_TYPE, GeneratorManager::getInstance()->getGeneratorList());
    }


    /**
     * @return Toggle
     */
    public function getAutoSaveToggle(): Toggle
    {
        return new Toggle('auto save', true);
    }


    /**
     * @return Toggle
     */
    public function getTeleportToggle(): Toggle
    {
        return new Toggle('teleport', true);
    }


    /**
     * @return Dropdown
     */
    public function getDifficultyDropdown(): Dropdown
    {
        return new Dropdown('set difficulty level', [
            World::DIFFICULTY_PEACEFUL => 'peaceful',
            World::DIFFICULTY_EASY => 'easy',
            World::DIFFICULTY_NORMAL => 'normal',
            World::DIFFICULTY_HARD => 'hard'
        ]);
    }


    /**
     * @return Slider
     */
    public function getTimeSlider(): Slider
    {
        return new Slider('set time', 0, World::TIME_FULL);
    }
}
