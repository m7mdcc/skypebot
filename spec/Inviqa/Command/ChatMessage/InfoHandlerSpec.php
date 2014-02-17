<?php

namespace spec\Inviqa\Command\ChatMessage;

use Inviqa\Command\ChatMessage\InfoHandler;
use Inviqa\SkypeCommandInterface;
use Inviqa\SkypeEngine;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class InfoHandlerSpec extends ObjectBehavior
{
    function let(SkypeEngine $engine)
    {
        $this->setEngine($engine);
    }

    function it_does_not_act_without_info_command(
        SkypeCommandInterface $chatname, SkypeCommandInterface $handle, SkypeCommandInterface $body, $engine
    ) {
        $body->getValue()->willReturn('not_info');
        $engine->invoke(Argument::any())->shouldNotBeCalled();
        $this->handle($chatname, $handle, $body);
    }

    function it_responds_to_info_command_with_URLs_message(
        SkypeCommandInterface $chatname, SkypeCommandInterface $handle, SkypeCommandInterface $body, $engine
    ) {
        $body->getValue()->willReturn(':info');
        $chatname->getValue()->willReturn('chatname');
        $expected = sprintf(
            'CHATMESSAGE chatname For github integration, add this URL; %s?id=chatname as a commit hook in your github repository.\n\nFor Jenkins Notifications use %s?id=chatname',
            InfoHandler::GITHUB_URL_BASE,
            InfoHandler::JENKINS_URL_BASE
        );
        var_dump('expected', $expected);
        $engine->invoke($expected)->shouldBeCalled();
        $this->handle($chatname, $handle, $body);
    }
}
