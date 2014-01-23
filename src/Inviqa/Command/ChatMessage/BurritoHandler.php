<?php

namespace Inviqa\Command\ChatMessage;

use Inviqa\SkypeCommandInterface;

class BurritoHandler extends AbstractHandler implements ChatMessageHandlerInterface
{
    const BURRITO_URL = "http://invi.qa/sheffield-burrito";
    
    public function handle(SkypeCommandInterface $chatname, SkypeCommandInterface $handle, SkypeCommandInterface $body)
    {
        if (!$this->firstWordIs(':burritos?', $body->getValue())) {
            return;
        }
        
        $this->engine->invoke("CHATMESSAGE {$chatname->getValue()} " . self::BURRITO_URL);
    }
}
