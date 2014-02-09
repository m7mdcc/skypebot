<?php

namespace spec\Inviqa\Command\ChatMessage;

use Inviqa\SkypeCommandInterface;
use Inviqa\SkypeEngine;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class DeployHandlerSpec extends ObjectBehavior
{
    function let(SkypeEngine $engine)
    {
        $this->setEngine($engine);
    }

    function it_does_not_act_without_deploy_command(
        SkypeCommandInterface $chatname, SkypeCommandInterface $handle, SkypeCommandInterface $body
    ) {
        $body->getValue()->willReturn('not_deploy');
        $chatname->getValue()->shouldNotBeCalled();
        $this->handle($chatname, $handle, $body);
    }

    function it_responds_to_the_deploy_keywork()
    {

    }
}
