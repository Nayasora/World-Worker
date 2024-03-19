<?php

namespace Kimi\form\content\menu;

use Kimi\form\lib\menu\Button;
use Kimi\form\content\Content;
use Kimi\OperationsList;

class OperationsContent extends Content implements MenuContentInterface
{
    public const CONTENT_NAME = 'operation list';

    /**
     * @return Button[]
     */
    public function getList(): array
    {
        return [
            $this->getGenerateButton(),
            $this->getRenameButton(),
            $this->getDeleteButton(),
            $this->getLoadButton(),
            $this->getUnloadButton()
        ];
    }


    /**
     * @return string
     */
    public function getName(): string
    {
        return self::CONTENT_NAME;
    }


    /**
     * @return string
     */
    public function getType(): string
    {
        return Content::TYPE_MENU;
    }


    /**
     * @return Button
     */
    public function getGenerateButton(): Button
    {
        return new Button(OperationsList::GENERATE_NAME, null);
    }


    /**
     * @return Button
     */
    public function getRenameButton(): Button
    {
        return new Button(OperationsList::RENAME_NAME, null);
    }


    /**
     * @return Button
     */
    public function getDeleteButton(): Button
    {
        return new Button(OperationsList::DELETE_NAME, null);
    }


    /**
     * @return Button
     */
    public function getLoadButton(): Button
    {
        return new Button(OperationsList::LOAD_NAME, null);
    }


    /**
     * @return Button
     */
    public function getUnloadButton(): Button
    {
        return new Button(OperationsList::UNLOAD_NAME, null);
    }
}
