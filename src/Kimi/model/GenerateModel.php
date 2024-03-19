<?php

namespace Kimi\model;

use Closure;
use Kimi\form\lib\CustomFormResponse;
use Kimi\exception\ContentException;
use Kimi\exception\GeneratorException;
use Kimi\exception\ModelException;
use Kimi\form\content\custom\GenerateContent;
use Kimi\generator\GeneratorManager;
use Kimi\generator\VoidGenerator;
use Kimi\transfer\GenerateFormTransfer;
use Kimi\transfer\TransferInterface;
use pocketmine\block\VanillaBlocks;
use pocketmine\player\Player;
use pocketmine\world\BlockTransaction;
use pocketmine\world\World;
use pocketmine\world\WorldCreationOptions;

class GenerateModel extends Model
{
    /**
     * @return Closure
     */
    public function processResponse(): Closure
    {
        return function (Player $player, CustomFormResponse $customFormResponse): void {
            $response = GenerateFormTransfer::transfer($customFormResponse);

            if ($preventions = $response->getValidationWarnings()) {
                $this->sendPreventionForm($player, new GenerateContent(), $preventions);
                return;
            }

            $this->execute($player, $response);
        };
    }


    /**
     * @param Player $player
     * @param GenerateFormTransfer $response
     * @return void
     * @throws GeneratorException
     * @throws ModelException
     * @throws ContentException
     */
    public function execute(Player $player, TransferInterface $response): void
    {
        $worldName = $response->getWorldName();

        if ($this->getWorldManager()->isWorldGenerated($worldName)) {
            $this->sendPreventionForm($player, new GenerateContent(), ['world ' . $worldName . 'already generated']);
            return;
        }

        $world = $this->generateNewWorld($worldName, $response);
        $world->setAutoSave($response->getAutoSave());
        $world->setTime($response->getTime());
        $spawnLocation = $world->getSpawnLocation();

        if ($response->getTeleport()) {
            $player->teleport($spawnLocation->up());

            if ($response->getWorldType() === VoidGenerator::GENERATOR_NAME) {
                (new BlockTransaction($world))->addBlock($spawnLocation->down(), VanillaBlocks::GRASS());
            }
        }

        $player->sendMessage("world $worldName successfully generated!");
    }


    /**
     * @param string $worldName
     * @param GenerateFormTransfer $response
     * @return World
     * @throws GeneratorException
     */
    public function generateNewWorld(string $worldName, GenerateFormTransfer $response): World
    {
        $generatorManager = GeneratorManager::getInstance();
        $creationOptions = WorldCreationOptions::create();

        $creationOptions->setGeneratorClass($generatorManager->getGeneratorClassByType($response->getWorldType()));
        $creationOptions->setDifficulty($response->getWorldDifficulty());

        return  $generatorManager->generate($worldName, $creationOptions);
    }
}
