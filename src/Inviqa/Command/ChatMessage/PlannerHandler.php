<?php

namespace Inviqa\Command\ChatMessage;

use Inviqa\SkypeCommandInterface;

class PlannerHandler extends AbstractHandler implements ChatMessageHandlerInterface
{
    const PLANNER_URL = 'http://invi.qa/planner-2014';

    public function handle(SkypeCommandInterface $chatname, SkypeCommandInterface $handle, SkypeCommandInterface $body)
    {
        if (!$this->firstWordIs(':planner', $body->getValue())) {
            return;
        }

        $this->engine->invoke("CHATMESSAGE {$chatname->getValue()} " . self::PLANNER_URL);
    }
}
