<?php

namespace Inviqa\Command\ChatMessage;

use Inviqa\SkypeEngine;

abstract class AbstractHandler
{
    protected $engine;

    public function setEngine(SkypeEngine $engine)
    {
        $this->engine = $engine;
    }

    protected function firstWordIs($needle, $haystack)
    {
        return strtok($haystack, ' ') === $needle;
    }
}
