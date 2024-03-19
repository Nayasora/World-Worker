<?php

namespace Kimi\form\content\custom;

use Kimi\form\component\WorldListComponent;
use Kimi\form\content\Content;
use Kimi\form\content\PreventionContent;
use Kimi\OperationsList;

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
