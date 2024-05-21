<?php

namespace SerenitySun\WorldWorker\model;

use Closure;
use SerenitySun\WorldWorker\exception\ContentException;
use SerenitySun\WorldWorker\exception\GeneratorException;
use SerenitySun\WorldWorker\exception\ModelException;
use SerenitySun\WorldWorker\exception\WorldGenerationException;
use SerenitySun\WorldWorker\form\content\custom\GenerateContent;
use SerenitySun\WorldWorker\form\lib\CustomFormResponse;
use SerenitySun\WorldWorker\generator\GeneratorManager;
use SerenitySun\WorldWorker\generator\VoidGenerator;
use SerenitySun\WorldWorker\transfer\GenerateFormTransfer;
use SerenitySun\WorldWorker\transfer\TransferInterface;
use pocketmine\block\VanillaBlocks;
use pocketmine\player\Player;
use pocketmine\world\format\Chunk;
use pocketmine\world\World;
use pocketmine\world\WorldCreationOptions;

final class GenerateModel extends Model
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
            $this->sendPreventionForm($player, new GenerateContent(), ["world $worldName already generated"]);
            return;
        }

        $world = $this->generateNewWorld($worldName, $response);
        $world->setAutoSave($response->getAutoSave());

        $world->setTime($response->getTime());
        if ($response->getStopTime()) {
            $world->stopTime();
        }
        $spawnLocation = $world->getSpawnLocation();

        if ($response->getTeleport()) {
            $player->teleport($spawnLocation->up());
        }
        $this->presetSpawn($response->getWorldType(), $world);

        $player->sendMessage("world $worldName successfully generated!");
    }

    /**
     * @param string $worldType
     * @param World $world
     * @return void
     */
    private function presetSpawn(string $worldType, World $world): void
    {
        $spawnLocation = $world->getSpawnLocation();
        $worldName = $world->getFolderName();

        if ($worldType == VoidGenerator::GENERATOR_NAME) {
            $world->requestChunkPopulation(
                $spawnLocation->getX() >> Chunk::COORD_BIT_SIZE,
                $spawnLocation->getZ() >> Chunk::COORD_BIT_SIZE,
                null
            )->onCompletion(function () use ($world) {
                $world->setBlock($world->getSpawnLocation()->asVector3(), VanillaBlocks::GRASS());
            }, static function () use ($worldName) {
                //не должно срабатывать
                throw new WorldGenerationException('world '. $worldName . ' generation failed');
            });
        }
    }

    /**
     * @param string $worldName
     * @param GenerateFormTransfer $response
     * @return World
     * @throws GeneratorException
     */
    private function generateNewWorld(string $worldName, GenerateFormTransfer $response): World
    {
        $generatorManager = GeneratorManager::getInstance();
        $creationOptions = WorldCreationOptions::create();

        $creationOptions->setGeneratorClass($generatorManager->getGeneratorClassByType($response->getWorldType()));
        $creationOptions->setDifficulty($response->getWorldDifficulty());

        return  $generatorManager->generate($worldName, $creationOptions);
    }
}
