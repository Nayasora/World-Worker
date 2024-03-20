<?php

namespace Kimi\command;

use Kimi\exception\ContentException;
use Kimi\form\content\menu\OperationsContent;
use Kimi\form\Form;
use Kimi\model\OperationListModel;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\player\Player;

class WorkerCommand extends Command
{
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

        if (!$sender->hasPermission('worker.command')) {
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
