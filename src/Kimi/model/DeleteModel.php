<?php

namespace Kimi\model;

use Closure;
use Kimi\form\lib\CustomFormResponse;
use Kimi\exception\ContentException;
use Kimi\exception\ModelException;
use Kimi\form\content\custom\DeleteContent;
use Kimi\form\content\modal\DeleteConfirmContent;
use Kimi\transfer\BootFormTransfer;
use Kimi\transfer\ConfirmTransfer;
use Kimi\transfer\TransferInterface;
use pocketmine\player\Player;
use pocketmine\Server;
use pocketmine\world\World;

final class DeleteModel extends Model
{
    private bool   $confirmed = false;
    private string $worldName;

    /**
     * @return Closure
     */
    public function processResponse(): Closure
    {
        if (!$this->confirmed) {
            $this->confirmed = true;
            return $this->getChooseAction();
        }
        $this->confirmed = false;
        return $this->getConfirmationAction();
    }


    /**
     * @return Closure
     */
    public function getConfirmationAction(): Closure
    {
        return function (Player $player, bool $choice): void {
            $this->execute($player, ConfirmTransfer::transfer($choice));
        };
    }


    /**
     * @return Closure
     */
    public function getChooseAction(): Closure
    {
        return function (Player $player, CustomFormResponse $customFormResponse): void {
            $this->worldName = BootFormTransfer::transfer($customFormResponse)->getWorldName();
            $this->sendForm($player, new DeleteConfirmContent($this->worldName));
        };
    }


    /**
     * @param Player $player
     * @param ConfirmTransfer $response
     * @return void
     * @throws ModelException|ContentException
     */
    public function execute(Player $player, TransferInterface $response): void
    {
        if (!$response->getValue()) {
            $this->sendForm($player, new DeleteContent());
            return;
        }

        if (!$this->worldManager->isWorldGenerated($this->worldName)) {
            $this->sendPreventionForm($player, new DeleteContent(), ["$this->worldName is a incorrect world"]);
            return;
        }

        $world = $this->getLoadedWorld($this->worldName);

        if ($world->getFolderName() === $this->worldManager->getDefaultWorld()->getFolderName()) {
            $this->sendPreventionForm($player, new DeleteContent(), ["$this->worldName is a default world"]);
            return;
        }

        $dataPath = Server::getInstance()->getDataPath();

        if (!$this->worldManager->unloadWorld($world)) {
            $this->sendPreventionForm($player, new DeleteContent(), ["$this->worldName can not be safely unloaded"]);
            return;
        }
        $this->deleteFolder($dataPath.'worlds/'.$world->getFolderName());
        $player->sendMessage('world ' . $this->worldName . ' successfully deleted');
    }


    /**
     * @throws ModelException
     */
    public function getLoadedWorld(string $worldName): World
    {
        if (!$this->worldManager->isWorldLoaded($worldName)) {
            $this->worldManager->loadWorld($worldName);
        }

        $world = $this->worldManager->getWorldByName($worldName);

        if (!$world) {
            throw new ModelException('can not get a world by name');
        }

        return $world;
    }


    /**
     * @param string $path
     * @return bool
     */
    public function deleteFolder(string $path): bool
    {
        $files = array_diff(scandir($path), array('.','..'));

        foreach ($files as $file) {
            (is_dir("$path/$file")) ? $this->deleteFolder("$path/$file") : unlink("$path/$file");
        }
        return rmdir($path);
    }
}
