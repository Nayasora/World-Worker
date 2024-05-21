<?php

namespace SerenitySun\WorldWorker\command;

use pocketmine\command\CommandMap;
use pocketmine\utils\SingletonTrait;

class CommandManager
{
    use SingletonTrait;

    /**
     * @param  CommandMap $map
     * @return void
     */
    public function registerCommands(CommandMap $map): void
    {
        $workerCommand = new WorkerCommand("worker");
        $workerCommand->setPermission('worker.command');
        $map->register("worker", $workerCommand);
    }
}
