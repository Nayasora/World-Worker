<?php

namespace SerenitySun\WorldWorker\model;

use Closure;
use Exception;
use SerenitySun\WorldWorker\exception\ContentException;
use SerenitySun\WorldWorker\exception\ModelException;
use SerenitySun\WorldWorker\form\content\custom\LoadContent;
use SerenitySun\WorldWorker\form\lib\CustomFormResponse;
use SerenitySun\WorldWorker\transfer\BootFormTransfer;
use SerenitySun\WorldWorker\transfer\TransferInterface;
use pocketmine\player\Player;

final class LoadModel extends Model
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

        if (!$worldName) {
            return;
        }

        try {
            $this->getWorldManager()->loadWorld($worldName);
        } catch (Exception $exception) {
            $this->sendPreventionForm($player, new LoadContent(), [$exception->getMessage()]);
        }

        $player->sendMessage("world $worldName successfully loaded");
    }
}
