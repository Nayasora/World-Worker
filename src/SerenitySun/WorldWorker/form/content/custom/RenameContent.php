<?php

namespace SerenitySun\WorldWorker\form\content\custom;

use SerenitySun\WorldWorker\form\component\WorldListComponent;
use SerenitySun\WorldWorker\form\content\Content;
use SerenitySun\WorldWorker\form\content\PreventionContent;
use SerenitySun\WorldWorker\form\lib\element\Input;
use Kimi\WorldWorker\OperationsList;

class RenameContent extends PreventionContent implements CustomContentInterface
{
    use WorldListComponent;

    /**
     * @return array
     */
    public function getElements(): array
    {
        $body = [
            $this->getNewNameInput(),
            $this->getAllWorldsList()
        ];

        if ($this->hasPrevention()) {
            $this->appendComponent($body, $this->getPreventions());
        }

        return $body;
    }


    /**
     * @return string
     */
    public function getName(): string
    {
        return OperationsList::RENAME_NAME;
    }


    /**
     * @return string
     */
    public function getType(): string
    {
        return Content::TYPE_CUSTOM;
    }


    /**
     * @return Input
     */
    public function getNewNameInput(): Input
    {
        return new Input('input new world name', 'new name');
    }
}
