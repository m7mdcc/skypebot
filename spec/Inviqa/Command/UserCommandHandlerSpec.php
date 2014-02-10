<?php

namespace spec\Inviqa\Command;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Inviqa\SkypeCommandInterface;

class UserCommandHandlerSpec extends ObjectBehavior
{
    function it_rejects_unrecognised_commands(SkypeCommandInterface $command)
    {
        $command->getCommand()->willReturn('not_user');
        $this->handleCommand($command)->shouldReturn(false);
    }

    function it_responds_to_a_user_command(SkypeCommandInterface $command)
    {
        $command->getCommand()->willReturn('USER');
        $command->getArgument()->willReturn(null);
        $this->handleCommand($command)->shouldReturn(true);
    }
}
