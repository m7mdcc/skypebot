<?php

namespace spec\Inviqa\Command\ChatMessage;

use Inviqa\SkypeCommandInterface;
use Inviqa\SkypeEngine;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class PingHandlerSpec extends ObjectBehavior
{
    function let(SkypeEngine $engine)
    {
        $this->setEngine($engine);
    }

    function it_does_not_act_without_ping_command(
        SkypeCommandInterface $chatname, SkypeCommandInterface $handle, SkypeCommandInterface $body, $engine
    ) {
        $body->getValue()->willReturn('not_ping');
        $engine->invoke(Argument::any())->shouldNotBeCalled();
        $this->handle($chatname, $handle, $body);
    }

    function it_responds_to_ping_command(
        SkypeCommandInterface $chatname, SkypeCommandInterface $handle, SkypeCommandInterface $body, $engine
    ) {
        $body->getValue()->willReturn(':ping');
        $chatname->getValue()->willReturn('chat_name');
        $engine->invoke('CHATMESSAGE chat_name Pong!')->shouldBeCalled();
        $this->handle($chatname, $handle, $body);
    }
}
