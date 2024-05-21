<?php

namespace SerenitySun\WorldWorker\form\content\custom;

use SerenitySun\WorldWorker\form\component\WorldListComponent;
use SerenitySun\WorldWorker\form\content\Content;
use SerenitySun\WorldWorker\form\content\PreventionContent;
use SerenitySun\WorldWorker\OperationsList;

class DeleteContent extends PreventionContent implements CustomContentInterface
{
    use WorldListComponent;

    /**
     * @return array
     */
    public function getElements(): array
    {
        $body = [
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
        return OperationsList::DELETE_NAME;
    }


    /**
     * @return string
     */
    public function getType(): string
    {
        return Content::TYPE_CUSTOM;
    }
}
