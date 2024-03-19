<?php

namespace Kimi\model;

use Closure;
use InvalidArgumentException;
use Kimi\form\lib\menu\Button;
use Kimi\exception\ContentException;
use Kimi\form\content\custom\DeleteContent;
use Kimi\form\content\custom\GenerateContent;
use Kimi\form\content\custom\LoadContent;
use Kimi\form\content\custom\RenameContent;
use Kimi\form\content\custom\UnloadContent;
use Kimi\form\Form;
use Kimi\OperationsList;
use Kimi\transfer\MainFormTransfer;
use Kimi\transfer\TransferInterface;
use pocketmine\player\Player;

class OperationListModel extends Model
{
    /**
     * @return Closure
     */
    public function processResponse(): Closure
    {
        return fn (Player $player, Button $button) =>
            $this->execute($player, MainFormTransfer::transfer($button));
    }


    /**
     * @param Player $player
     * @param MainFormTransfer $response
     * @return void
     * @throws ContentException
     */
    public function execute(Player $player, TransferInterface $response): void
    {
        $form = match ($response->getOperationName()) {
            OperationsList::GENERATE_NAME => (new Form(
                new GenerateContent(),
                new GenerateModel()
            ))->newCustom(),
            OperationsList::RENAME_NAME => (new Form(
                new RenameContent(),
                new RenameModel()
            ))->newCustom(),
            OperationsList::DELETE_NAME => (new Form(
                new DeleteContent(),
                new DeleteModel()
            ))->newCustom(),
            OperationsList::LOAD_NAME => (new Form(
                new LoadContent(),
                new LoadModel()
            ))->newCustom(),
            OperationsList::UNLOAD_NAME => (new Form(
                new UnloadContent(),
                new UnloadModel()
            ))->newCustom(),
            default => throw new InvalidArgumentException('unknown operation received')
        };

        $player->sendForm($form);
    }
}
