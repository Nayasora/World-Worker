<?php

namespace SerenitySun\WorldWorker\form\content;

/**
 * Class appends the elements to a form
 */
abstract class Content
{
    public const TYPE_CUSTOM = "custom";
    public const TYPE_MENU   = "menu";
    public const TYPE_MODAL	 = "modal";

    /**
     * @return string
     */
    public function getTitle(): string
    {
        return 'world-worker';
    }


    /**
     * @param array $body
     * @param ...$components
     * @return void
     */
    public function appendComponent(array &$body, ...$components): void
    {
        foreach ($components as $component) {
            if (is_array($component)) {
                foreach ($component as $elements) {
                    $body[] = $elements;
                }
            } else {
                $body[] = $component;
            }
        }
    }


    /**
     * @return string
     */
    abstract public function getName(): string;


    /**
     * @return string
     */
    abstract public function getType(): string;
}
