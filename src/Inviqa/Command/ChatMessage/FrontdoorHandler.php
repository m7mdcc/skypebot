<?php

namespace Inviqa\Command\ChatMessage;

use Inviqa\SkypeCommandInterface;

class FrontdoorHandler extends AbstractHandler implements ChatMessageHandlerInterface
{
    public function handle(SkypeCommandInterface $chatname, SkypeCommandInterface $handle, SkypeCommandInterface $body)
    {
        if (!$this->firstWordIs(':frontdoor', $body->getValue())) {
            return;
        }
        
        $this->engine->invoke(
            sprintf(
                'CHATMESSAGE %s %s/VPN+Service#VPNService-Dynamicportredirection',
                $chatname->getValue(),
                WikiHandler::WIKI_URL
            )
        );
    }
}
