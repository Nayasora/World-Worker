<?php

namespace SerenitySun\WorldWorker\model;

use Closure;
use InvalidArgumentException;
use SerenitySun\WorldWorker\exception\ContentException;
use SerenitySun\WorldWorker\exception\ModelException;
use SerenitySun\WorldWorker\form\content\custom\RenameContent;
use SerenitySun\WorldWorker\form\lib\CustomFormResponse;
use SerenitySun\WorldWorker\transfer\RenameFormTransfer;
use Kimi\WorldWorker\transfer\TransferInterface;
use pocketmine\player\Player;
use pocketmine\Server;
use pocketmine\world\format\io\data\BaseNbtWorldData;

final class RenameModel extends Model
{
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * @return Closure
     */
    public function processResponse(): Closure
    {
        return function (Player $player, CustomFormResponse $customFormResponse) {
            $response = RenameFormTransfer::transfer($customFormResponse);

            $this->execute($player, $response);
        };
    }


    /**
     * @param Player $player
     * @param RenameFormTransfer $response
     * @return void
     * @throws ModelException
     * @throws ContentException
     */
    public function execute(Player $player, TransferInterface $response): void
    {
        $lastName     = $response->getLastName();
        $newName      = $response->getNewName();

        if ($warnings = $response->getValidationWarnings()) {
            $this->sendPreventionForm($player, new RenameContent(), $warnings);
            return;
        }

        if (!$this->checkIsGenerated($player, $lastName)) {
            return;
        }

        if (!$this->unloadWorld($player, $lastName)) {
            return;
        }

        try {
            $this->renameWorld($lastName, $newName);
        } catch (ModelException $exception) {
            $this->sendPreventionForm($player, new RenameContent(), [$exception->getMessage()]);
            return;
        }

        $player->sendMessage("world {$lastName} successfully renamed to {$newName}");
    }


    /**
     * @param Player $player
     * @param string $worldName
     * @return bool
     * @throws ModelException|ContentException
     */
    private function checkIsGenerated(Player $player, string $worldName): bool
    {
        if (!$this->worldManager->isWorldGenerated($worldName)) {
            $this->sendPreventionForm($player, new RenameContent(), ["world by name {$worldName} not found"]);
            return false;
        }

        return true;
    }


    /**
     * @param Player $player
     * @param string $worldName
     * @return bool
     * @throws ModelException|ContentException
     */
    private function unloadWorld(Player $player, string $worldName): bool
    {
        $world = $this->worldManager->getWorldByName($worldName);

        if ($world) {
            try {
                if (!$this->worldManager->unloadWorld($world)) {
                    $this->sendPreventionForm($player, new RenameContent(), ['can not unload this world']);
                    return false;
                }
            } catch (InvalidArgumentException $exception) {
                $this->sendPreventionForm($player, new RenameContent(), [$exception->getMessage()]);
                return false;
            }
        }

        return true;
    }


    /**
     * @param string $lastName
     * @param string $newName
     * @return void
     * @throws ModelException
     */
    private function renameWorld(string $lastName, string $newName): void
    {
        $worldPath = Server::getInstance()->getDataPath() . "worlds/";
        $renamed   = rename($worldPath.$lastName, $worldPath.$newName);

        if (!$renamed) {
            throw new ModelException('world is not renamed');
        }

        $loaded = $this->worldManager->loadWorld($newName);

        if (!$loaded) {
            throw new ModelException('world did not loaded');
        }

        $world     = $this->worldManager->getWorldByName($newName);
        $worldData = $world->getProvider()->getWorldData();

        if (!$worldData instanceof BaseNbtWorldData) {
            throw new ModelException('world not have nbt flags');
        }

        $worldData->getCompoundTag()->setString('LevelName', $newName);

        $this->worldManager->unloadWorld($world);
        $this->worldManager->loadWorld($newName);
    }
}
