<?php

namespace spec\Inviqa\Command\ChatMessage;

use Inviqa\Command\ChatMessage\WikiHandler;
use Inviqa\SkypeCommandInterface;
use Inviqa\SkypeEngine;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class WikiHandlerSpec extends ObjectBehavior
{
    function let(SkypeEngine $engine)
    {
        $this->setEngine($engine);
    }

    function it_does_not_act_without_wiki_command(
        SkypeCommandInterface $chatname, SkypeCommandInterface $handle, SkypeCommandInterface $body, $engine
    ) {
        $body->getValue()->willReturn('not_wiki');
        $engine->invoke(Argument::any())->shouldNotBeCalled();
        $this->handle($chatname, $handle, $body);
    }

    function it_responds_to_wiki_command_with_a_parameter(
        SkypeCommandInterface $chatname, SkypeCommandInterface $handle, SkypeCommandInterface $body, $engine
    ) {
        $body->getValue()->willReturn(':wiki page-name');
        $chatname->getValue()->willReturn('chat_name');
        $engine->invoke('CHATMESSAGE chat_name ' . WikiHandler::WIKI_URL . '/page-name')->shouldBeCalled();
        $this->handle($chatname, $handle, $body);
    }

    function it_warns_if_no_parameter_is_supplied(
        SkypeCommandInterface $chatname, SkypeCommandInterface $handle, SkypeCommandInterface $body, $engine
    ) {
        $body->getValue()->willReturn(':wiki');
        $chatname->getValue()->willReturn('chat_name');
        $engine->invoke('CHATMESSAGE chat_name You must supply a wiki name, e.g. :wiki Incubator')->shouldBeCalled();
        $this->handle($chatname, $handle, $body);
    }
}
