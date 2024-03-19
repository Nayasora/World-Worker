<?php

namespace Kimi;

use pocketmine\Server;

class WorldManager
{
    public const STATUS_UNLOADED = 0;
    public const STATUS_LOADED   = 1;

    /**
     * @return string[]
     */
    public static function getAllWorlds(): array
    {
        $folderList = array_diff(
            scandir(Server::getInstance()->getDataPath() . '/worlds'),
            ['.', '..']
        );

        $worldList = [];

        foreach ($folderList as $worldName) {
            $generated = Server::getInstance()->getWorldManager()->isWorldGenerated($worldName);

            if ($generated) {
                $worldList[] = $worldName;
            }
        }

        return $worldList;
    }


    /**
     * @return string[]
     */
    public static function getWorldList(int $status = self::STATUS_LOADED): array
    {
        $allWorlds      = WorldManager::getAllWorlds();
        $loadedList     = [];

        foreach ($allWorlds as $world) {
            if (Server::getInstance()->getWorldManager()->isWorldLoaded($world)) {
                $loadedList[] = $world;
            }
        }

        return match ($status) {
            self::STATUS_LOADED   => $loadedList,
            self::STATUS_UNLOADED => array_values(array_diff($allWorlds, $loadedList)),
            default => []
        };
    }
}
