<?php

namespace Kimi\model;

use Closure;
use Exception;
use Kimi\form\lib\CustomFormResponse;
use Kimi\exception\ContentException;
use Kimi\exception\ModelException;
use Kimi\form\content\custom\LoadContent;
use Kimi\transfer\BootFormTransfer;
use Kimi\transfer\TransferInterface;
use pocketmine\player\Player;

final class LoadModel extends Model
{
    /**
     * @return Closure
     */
    protected function processResponse(): Closure
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
    protected function execute(Player $player, TransferInterface $response): void
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
