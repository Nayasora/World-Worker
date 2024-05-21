<?php

namespace SerenitySun\WorldWorker\model;

use Closure;
use SerenitySun\WorldWorker\exception\ContentException;
use SerenitySun\WorldWorker\exception\ModelException;
use SerenitySun\WorldWorker\form\content\custom\DeleteContent;
use SerenitySun\WorldWorker\form\content\modal\DeleteConfirmContent;
use SerenitySun\WorldWorker\form\lib\CustomFormResponse;
use SerenitySun\WorldWorker\transfer\BootFormTransfer;
use SerenitySun\WorldWorker\transfer\ConfirmTransfer;
use SerenitySun\WorldWorker\transfer\TransferInterface;
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
     * @return Closure
     */
    private function getConfirmationAction(): Closure
    {
        return function (Player $player, bool $choice): void {
            $this->execute($player, ConfirmTransfer::transfer($choice));
        };
    }


    /**
     * @return Closure
     */
    private function getChooseAction(): Closure
    {
        return function (Player $player, CustomFormResponse $customFormResponse): void {
            $this->worldName = BootFormTransfer::transfer($customFormResponse)->getWorldName();
            $this->sendForm($player, new DeleteConfirmContent($this->worldName));
        };
    }


    /**
     * @throws ModelException
     */
    private function getLoadedWorld(string $worldName): World
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
     * @return void
     */
    private function deleteFolder(string $path): void
    {
        $files = array_diff(scandir($path), array('.','..'));

        foreach ($files as $file) {
            (is_dir("$path/$file")) ? $this->deleteFolder("$path/$file") : unlink("$path/$file");
        }
        rmdir($path);
    }
}
