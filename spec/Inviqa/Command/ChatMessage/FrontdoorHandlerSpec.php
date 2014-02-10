<?php

namespace spec\Inviqa\Command\ChatMessage;

use Inviqa\Command\ChatMessage\WikiHandler;
use Inviqa\SkypeCommandInterface;
use Inviqa\SkypeEngine;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class FrontdoorHandlerSpec extends ObjectBehavior
{
    function let(SkypeEngine $engine)
    {
        $this->setEngine($engine);
    }

    function it_does_not_act_without_frontdoor_command(
        SkypeCommandInterface $chatname, SkypeCommandInterface $handle, SkypeCommandInterface $body, $engine
    ) {
        $body->getValue()->willReturn('not_frontdoor');
        $engine->invoke(Argument::any())->shouldNotBeCalled();
        $this->handle($chatname, $handle, $body);
    }

    function it_responds_to_frontdoor_command_with_URL_message(
        SkypeCommandInterface $chatname, SkypeCommandInterface $handle, SkypeCommandInterface $body, $engine
    ) {
        $body->getValue()->willReturn(':frontdoor');
        $chatname->getValue()->willReturn('chat_name');
        $engine->invoke(
            'CHATMESSAGE chat_name ' . WikiHandler::WIKI_URL . '/VPN+Service#VPNService-Dynamicportredirection'
        )->shouldBeCalled();
        $this->handle($chatname, $handle, $body);
    }
}
