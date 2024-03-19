<?php

namespace Kimi\form\content\modal;

use Kimi\form\content\Content;

class DeleteConfirmContent extends Content implements ModalContentInterface
{
    public string $worldName;

    /**
     * @param string $worldName
     */
    public function __construct(string $worldName)
    {
        $this->worldName = $worldName;
    }


    /**
     * @return string
     */
    public function getName(): string
    {
        return 'confirmation to delete world';
    }


    /**
     * @return string
     */
    public function getType(): string
    {
        return Content::TYPE_MODAL;
    }


    /**
     * @return string
     */
    public function getLabel(): string
    {
        return 'Are you sure you want to delete this world ' . $this->worldName . '?';
    }
}
