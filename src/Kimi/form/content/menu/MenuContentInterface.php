<?php

namespace Kimi\form\content\menu;

use Kimi\form\lib\menu\Button;

interface MenuContentInterface
{
    /**
     * @return Button[]
     */
    public function getList(): array;
}
