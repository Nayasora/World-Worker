<?php

namespace Kimi\bootstrap;

use Exception;
use Kimi\command\CommandManager;
use Kimi\generator\GeneratorManager;
use Kimi\generator\VoidGenerator;
use pocketmine\plugin\PluginBase;

/**
 * Entry point class.
 */
class PluginLoader extends PluginBase
{
    /**
     * Initialize method.
     *
     * @return void
     * @throws Exception
     */
    public function onLoad(): void
    {
        $server = $this->getServer();

        CommandManager::getInstance()->registerCommands($server->getCommandMap());
        GeneratorManager::getInstance()->registerGenerator(
            VoidGenerator::class,
            VoidGenerator::GENERATOR_NAME
        );
    }
}
