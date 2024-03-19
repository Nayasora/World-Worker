<?php

namespace Kimi\model;

use Closure;
use Kimi\exception\ContentException;
use Kimi\exception\ModelException;
use Kimi\form\content\Content;
use Kimi\form\content\PreventionContent;
use Kimi\form\Form;
use Kimi\transfer\TransferInterface;
use pocketmine\player\Player;
use pocketmine\Server;
use pocketmine\world\WorldManager;

/**
 * Имеет в себе логику выполнения команд
 */
abstract class Model
{
    protected WorldManager $worldManager;

    public function __construct()
    {
        $this->worldManager = Server::getInstance()->getWorldManager();
    }


    /**
     * @param Player $player
     * @param Content $content
     * @return void
     * @throws ModelException|ContentException
     */
    public function sendForm(Player $player, Content $content): void
    {
        $form = new Form($content, $this);

        match ($content->getType()) {
            Content::TYPE_CUSTOM => $player->sendForm($form->newCustom()),
            Content::TYPE_MODAL  => $player->sendForm($form->newModal()),
            Content::TYPE_MENU   => $player->sendForm($form->newMenu()),
            default	=> throw new ModelException('invalid content type caught')
        };
    }


    /**
     * @throws ContentException
     * @throws ModelException
     */
    public function sendPreventionForm(Player $player, PreventionContent $content, array $preventions): void
    {
        $content->setPreventions($preventions);
        $this->sendForm($player, $content);
    }


    /**
     * @return WorldManager
     */
    public function getWorldManager(): WorldManager
    {
        return $this->worldManager;
    }


    /**
     * @return Closure
     */
    abstract protected function processResponse(): Closure;


    /**
     * @param Player $player
     * @param TransferInterface $response
     * @return void
     */
    abstract protected function execute(Player $player, TransferInterface $response): void;
}
