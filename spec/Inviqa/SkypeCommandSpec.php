<?php

namespace spec\Inviqa;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class SkypeCommandSpec extends ObjectBehavior
{
    function it_returns_null_values_when_initialised_with_an_empty_string()
    {
        $this->beConstructedWith('');
        $this->getCommand()->shouldReturn(null);
        $this->getName()->shouldReturn(null);
        $this->getArgument()->shouldReturn(null);
        $this->getValue()->shouldReturn(null);
    }

    function it_only_sets_a_command_when_initialised_with_no_spaces()
    {
        $this->beConstructedWith('commandname');
        $this->getCommand()->shouldReturn('commandname');
        $this->getName()->shouldReturn(null);
        $this->getArgument()->shouldReturn(null);
        $this->getValue()->shouldReturn(null);
    }

    function it_sets_a_command_and_a_name()
    {
        $this->beConstructedWith('command name');
        $this->getCommand()->shouldReturn('command');
        $this->getName()->shouldReturn('name');
        $this->getArgument()->shouldReturn(null);
        $this->getValue()->shouldReturn(null);
    }

    function it_sets_a_command_name_and_argument()
    {
        $this->beConstructedWith('command name argument');
        $this->getCommand()->shouldReturn('command');
        $this->getName()->shouldReturn('name');
        $this->getArgument()->shouldReturn('argument');
        $this->getValue()->shouldReturn(null);
    }

    function it_sets_all_properties()
    {
        $this->beConstructedWith('command name argument value');
        $this->getCommand()->shouldReturn('command');
        $this->getName()->shouldReturn('name');
        $this->getArgument()->shouldReturn('argument');
        $this->getValue()->shouldReturn('value');
    }

    function it_sets_value_with_spaces()
    {
        $this->beConstructedWith('command name argument value1 value2');
        $this->getCommand()->shouldReturn('command');
        $this->getName()->shouldReturn('name');
        $this->getArgument()->shouldReturn('argument');
        $this->getValue()->shouldReturn('value1 value2');
    }
}
