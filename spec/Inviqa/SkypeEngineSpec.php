<?php

namespace spec\Inviqa;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Inviqa\Command\CommandHandlerInterface;

class SkypeEngineSpec extends ObjectBehavior
{
    function let(DBusObjectMock $dbus)
    {
        $this->beConstructedWith($dbus);
    }

    function it_calls_dbus_with_message($dbus)
    {
        $dbus->invoke('test_message')->shouldBeCalled();
        $this->invoke('test_message');
    }

    function it_adds_itself_to_an_added_command(CommandHandlerInterface $command)
    {
        $command->setEngine($this)->shouldBeCalled();
        $this->addCommandHandler($command);
    }

    function it_calls_an_added_command_when_it_parses_a_message(CommandHandlerInterface $command)
    {
        $command->setEngine($this)->shouldBeCalled();
        $command->handleCommand(Argument::type('Inviqa\SkypeCommandInterface'))->shouldBeCalled();
        $this->addCommandHandler($command);
        $this->parse('cmd name arg val');
    }

    function it_stops_calling_commands_when_one_responds(
        CommandHandlerInterface $command1, CommandHandlerInterface $command2
    ) {
        $command1->setEngine($this)->shouldBeCalled();
        $command2->setEngine($this)->shouldBeCalled();

        $command1->handleCommand(Argument::type('Inviqa\SkypeCommandInterface'))->willReturn(true);
        $command2->handleCommand(Argument::any())->shouldNotBeCalled();

        $this->addCommandHandler($command1);
        $this->addCommandHandler($command2);

        $this->parse('cmd name arg val');
    }

    function it_calls_all_commands_if_none_respond(
        CommandHandlerInterface $command1, CommandHandlerInterface $command2, CommandHandlerInterface $command3
    ) {
        $command1->setEngine($this)->shouldBeCalled();
        $command2->setEngine($this)->shouldBeCalled();
        $command3->setEngine($this)->shouldBeCalled();

        $command1->handleCommand(Argument::any())->willReturn(false);
        $command2->handleCommand(Argument::any())->willReturn(false);
        $command3->handleCommand(Argument::any())->willReturn(false);

        $this->addCommandHandler($command1);
        $this->addCommandHandler($command2);
        $this->addCommandHandler($command3);

        $this->parse('cmd name arg val');
    }
}
