<?php

namespace Kimi\model;

use Closure;
use Kimi\form\lib\CustomFormResponse;
use InvalidArgumentException;
use Kimi\exception\ContentException;
use Kimi\exception\ModelException;
use Kimi\form\content\custom\UnloadContent;
use Kimi\transfer\BootFormTransfer;
use Kimi\transfer\TransferInterface;
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
