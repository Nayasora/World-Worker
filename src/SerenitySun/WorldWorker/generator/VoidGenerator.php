<?php

namespace SerenitySun\WorldWorker\generator;

use pocketmine\world\ChunkManager;
use pocketmine\world\generator\Generator;

/**
 * Void world generator. PocketMine doesn't have a void generator.
 */
class VoidGenerator extends Generator
{
    public const GENERATOR_NAME = 'void';

    public function __construct(int $seed, string $preset)
    {
        parent::__construct($seed, $preset);
    }


    /**
     * @param ChunkManager $world
     * @param int $chunkX
     * @param int $chunkZ
     * @return void
     */
    public function generateChunk(ChunkManager $world, int $chunkX, int $chunkZ): void
    {
        //nothing to do
    }


    /**
     * @param ChunkManager $world
     * @param int $chunkX
     * @param int $chunkZ
     * @return void
     */
    public function populateChunk(ChunkManager $world, int $chunkX, int $chunkZ): void
    {
        //nothing to do
    }
}
