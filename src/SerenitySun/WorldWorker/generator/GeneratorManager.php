<?php

namespace SerenitySun\WorldWorker\generator;

use Closure;
use SerenitySun\WorldWorker\exception\GeneratorException;
use SerenitySun\WorldWorker\exception\WorldGenerationException;
use pocketmine\Server;
use pocketmine\utils\SingletonTrait;
use pocketmine\world\generator\GeneratorManager as KernelManager;
use pocketmine\world\World;
use pocketmine\world\WorldCreationOptions;

/**
 * Class for managing worlds in plugin actions.
 */
class GeneratorManager
{
    use SingletonTrait;

    private array $customGenerators = [];


    /**
     * Generates transferred world
     *
     * @param string $worldName
     * @param WorldCreationOptions $creationOptions
     * @return World
     */
    public function generate(string $worldName, WorldCreationOptions $creationOptions): World
    {
        $worldManager = Server::getInstance()->getWorldManager();
        $generated    = $worldManager->generateWorld($worldName, $creationOptions);

        if (!$generated) {
            throw new WorldGenerationException('world already generated');
        }

        $generatedWorld = $worldManager->getWorldByName($worldName);

        if (!$generatedWorld) {
            throw new WorldGenerationException('failed world generation operation');
        }

        return $generatedWorld;
    }


    /**
     * @param string $type
     * @return string
     * @throws GeneratorException
     */
    public function getGeneratorClassByType(string $type): string
    {
        $generatorEntry = KernelManager::getInstance()->getGenerator($type);

        if (!$generatorEntry) {
            throw new GeneratorException('generator type ' . $type . 'is not registered');
        }

        return $generatorEntry->getGeneratorClass();
    }


    /**
     * @param string $className
     * @param string $name
     * @param Closure|null $validator
     * @param bool $overwrite
     * @return void
     * @throws GeneratorException
     */
    public function registerGenerator(
        string $className,
        string $name,
        Closure $validator = null,
        bool $overwrite = true
    ): void {
        $presetValidator = $validator ?? fn () => null;

        if (array_key_exists($name, $this->customGenerators)) {
            throw new GeneratorException('generator ' . $name . ' already registered');
        }

        KernelManager::getInstance()->addGenerator($className, $name, $presetValidator, $overwrite);
        $this->customGenerators[$name] = $className;
    }
}
