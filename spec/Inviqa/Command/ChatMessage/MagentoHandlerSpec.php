<?php

namespace spec\Inviqa\Command\ChatMessage;

use Inviqa\SkypeCommandInterface;
use Inviqa\SkypeEngine;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class MagentoHandlerSpec extends ObjectBehavior
{
    function let(SkypeEngine $engine)
    {
        $this->setEngine($engine);
    }

    function it_does_not_act_without_magento_command(
        SkypeCommandInterface $chatname, SkypeCommandInterface $handle, SkypeCommandInterface $body, $engine
    ) {
        $body->getValue()->willReturn('not_magento');
        $engine->invoke(Argument::any())->shouldNotBeCalled();
        $this->handle($chatname, $handle, $body);
    }

    function it_responds_to_magento_command(
        SkypeCommandInterface $chatname, SkypeCommandInterface $handle, SkypeCommandInterface $body, $engine
    ) {
        $body->getValue()->willReturn(':magento');
        $chatname->getValue()->willReturn('chat_name');
        $engine->invoke(Argument::that(function($arg) {
            return strpos('CHATMESSAGE chat_name ', $arg[0]) === 0;
        }))->shouldBeCalled();
        $this->handle($chatname, $handle, $body);
    }
}
