<?php

namespace SerenitySun\WorldWorker\model;

use Closure;
use InvalidArgumentException;
use SerenitySun\WorldWorker\exception\ContentException;
use SerenitySun\WorldWorker\exception\ModelException;
use SerenitySun\WorldWorker\form\content\custom\UnloadContent;
use SerenitySun\WorldWorker\form\lib\CustomFormResponse;
use SerenitySun\WorldWorker\transfer\BootFormTransfer;
use SerenitySun\WorldWorker\transfer\TransferInterface;
use pocketmine\player\Player;

final class UnloadModel extends Model
{
    /**
     * @return Closure
     */
    public function processResponse(): Closure
    {
        return function (Player $player, CustomFormResponse $customFormResponse): void {
            $response = BootFormTransfer::transfer($customFormResponse);

            $this->execute($player, $response);
        };
    }


    /**
     * @param Player $player
     * @param BootFormTransfer $response
     * @return void
     * @throws ModelException|ContentException
     */
    public function execute(Player $player, TransferInterface $response): void
    {
        $worldName = $response->getWorldName();
        $world     = $this->getWorldManager()->getWorldByName($worldName);

        if (!$world) {
            throw new ModelException("can not get the world $worldName");
        }

        try {
            $this->getWorldManager()->unloadWorld($world);
        } catch (InvalidArgumentException $exception) {
            $this->sendPreventionForm($player, new UnloadContent(), [$exception->getMessage()]);
            return;
        }

        $player->sendMessage("world $worldName successfully unloaded");
    }
}
