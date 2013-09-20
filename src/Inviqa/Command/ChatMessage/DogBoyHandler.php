<?php

namespace Inviqa\Command\ChatMessage;

use Inviqa\SkypeCommandInterface;

class DogBoyHandler extends AbstractHandler implements ChatMessageHandlerInterface
{
    public function handle(SkypeCommandInterface $chatname, SkypeCommandInterface $handle, SkypeCommandInterface $body)
    {
        $username = $handle->getValue();
        $roomName = $chatname->getValue();
        //special case for Andrew "dog boy" Baker
        if ($this->isUsernameADogboy($username) && $this->isRoomAverseToDogStories($roomName)) {
            $bodyText = $body->getValue();
            if (stristr(preg_replace('#[\W]#', '', $bodyText), 'dog') 
                || stristr($bodyText, 'toivo') 
                || stristr($bodyText, 'mansfield')
                || stristr($bodyText, 'robot')
            ) {
                $this->engine->invoke("CHATMESSAGE $roomName Potential dog story detected. :-O");
                $this->engine->invoke("ALTER CHAT $roomName KICK $username");
            }
        }
    }
    
    private function isUsernameADogboy($username)
    {
        return $username === 'abaker.inviqa';
    }
    
    private function isRoomAverseToDogStories($roomName)
    {
        return $roomName === '#ben.longden/$rowan.merewood;1d3ab49e7f5995e1';
    }
}
