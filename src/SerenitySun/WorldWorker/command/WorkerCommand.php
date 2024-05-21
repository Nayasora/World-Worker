<?php

namespace SerenitySun\WorldWorker\command;

use SerenitySun\WorldWorker\exception\ContentException;
use SerenitySun\WorldWorker\form\content\menu\OperationsContent;
use Kimi\WorldWorker\form\Form;
use Kimi\WorldWorker\model\OperationListModel;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\player\Player;
use pocketmine\plugin\PluginOwned;
use pocketmine\plugin\PluginOwnedTrait;

class WorkerCommand extends Command implements PluginOwned
{
    use PluginOwnedTrait;

    public function __construct(string $name)
    {
        parent::__construct(
            $name,
            "§r open operation list",
            "§r this command not support any args"
        );
    }


    /**
     * execution of the command
     *
     * @param CommandSender $sender
     * @param string $commandLabel
     * @param array<int, string> $args
     * @return void
     * @throws ContentException
     */
    public function execute(CommandSender $sender, string $commandLabel, array $args): void
    {
        if (!$sender instanceof Player) {
            return;
        }

        if (!$sender->hasPermission('world-worker.worker')) {
            $sender->sendMessage("need a permission");
            return;
        }

        $form = new Form(
            new OperationsContent(),
            new OperationListModel()
        );

        $sender->sendForm($form->newMenu());
    }
}
