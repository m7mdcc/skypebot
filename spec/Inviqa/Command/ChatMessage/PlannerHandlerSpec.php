<?php

namespace spec\Inviqa\Command\ChatMessage;

use Inviqa\Command\ChatMessage\PlannerHandler;
use Inviqa\SkypeCommandInterface;
use Inviqa\SkypeEngine;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class PlannerHandlerSpec extends ObjectBehavior
{
    function let(SkypeEngine $engine)
    {
        $this->setEngine($engine);
    }

    function it_does_not_act_without_planner_command(
        SkypeCommandInterface $chatname, SkypeCommandInterface $handle, SkypeCommandInterface $body, $engine
    ) {
        $body->getValue()->willReturn('not_planner');
        $engine->invoke(Argument::any())->shouldNotBeCalled();
        $this->handle($chatname, $handle, $body);
    }

    function it_responds_to_planner_command_with_URL_message(
        SkypeCommandInterface $chatname, SkypeCommandInterface $handle, SkypeCommandInterface $body, $engine
    ) {
        $body->getValue()->willReturn(':planner');
        $chatname->getValue()->willReturn('chat_name');
        $engine->invoke('CHATMESSAGE chat_name ' . PlannerHandler::PLANNER_URL)->shouldBeCalled();
        $this->handle($chatname, $handle, $body);
    }
}
