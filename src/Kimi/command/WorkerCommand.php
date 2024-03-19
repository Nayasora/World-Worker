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
            "§r открыть форму для работы с мирами",
            "§r эта команда не требует аргументов"
        );
    }


    /**
     * execution of the command
     *
     * @param CommandSender $sender
     * @param string $commandLabel
     * @param array $args
     * @return void
     * @throws ContentException
     */
    public function execute(CommandSender $sender, string $commandLabel, array $args): void
    {
        if (!$sender instanceof Player) {
            return;
        }

        $form = new Form(
            new OperationsContent(),
            new OperationListModel()
        );

        $sender->sendForm($form->newMenu());
    }
}
