<?php

namespace SerenitySun\WorldWorker\form\content\menu;

use SerenitySun\WorldWorker\form\lib\menu\Button;

interface MenuContentInterface
{
    /**
     * @return Button[]
     */
    public function getList(): array;
}
