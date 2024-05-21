<?php

namespace SerenitySun\WorldWorker\form\content\custom;

use SerenitySun\WorldWorker\form\component\WorldListComponent;
use SerenitySun\WorldWorker\form\content\Content;
use SerenitySun\WorldWorker\form\content\PreventionContent;
use SerenitySun\WorldWorker\OperationsList;

class LoadContent extends PreventionContent implements CustomContentInterface
{
    use WorldListComponent;

    /**
     * @return array
     */
    public function getElements(): array
    {
        $listDropdown = $this->getUnloadedWorlds();
        $body 		  = [];

        if (!$listDropdown) {
            $this->setPrevention('all worlds loaded');
        } else {
            $body[] = $listDropdown;
        }

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
        return OperationsList::LOAD_NAME;
    }


    /**
     * @return string
     */
    public function getType(): string
    {
        return Content::TYPE_CUSTOM;
    }
}
