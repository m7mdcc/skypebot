<?php

namespace spec\Inviqa\Command;

use Inviqa\Command\ChatMessage\ChatMessageHandlerInterface;
use Inviqa\SkypeCommandInterface;
use Inviqa\SkypeEngine;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class ChatMessageCommandHandlerSpec extends ObjectBehavior
{
    function let(SkypeEngine $engine)
    {
        $this->setEngine($engine);
    }

    function it_rejects_unrecognised_commands(SkypeCommandInterface $command)
    {
        $command->getCommand()->willReturn('not_chat_message');
        $this->handleCommand($command)->shouldReturn(false);
    }

    function it_responds_to_a_chatmessage_command(SkypeCommandInterface $command)
    {
        $command->getCommand()->willReturn('CHATMESSAGE');
        $command->getName()->willReturn('name');
        $command->getArgument()->willReturn(null);

        $this->handleCommand($command)->shouldReturn(true);
    }

    function it_acknowledges_a_seen_message(SkypeCommandInterface $command, $engine)
    {
        $command->getCommand()->willReturn('CHATMESSAGE');
        $command->getName()->willReturn('name');
        $command->getArgument()->willReturn(null);

        $engine->invoke('SET CHATMESSAGE name SEEN')->shouldBeCalled();
        $this->handleCommand($command);
    }

    function it_adds_the_engine_to_an_added_message_handler(
        ChatMessageHandlerInterface $messageHandler, $engine
    ) {
        $messageHandler->setEngine($engine)->shouldBeCalled();
        $this->add($messageHandler);
    }

    function it_supports_a_fluid_interface_when_adding_message_handlers(
        ChatMessageHandlerInterface $messageHandler
    ) {
        $this->add($messageHandler)->shouldReturn($this);
    }

    function it_does_not_run_message_handlers_when_command_argument_is_not_status(
        SkypeCommandInterface $command, ChatMessageHandlerInterface $messageHandler
    ) {
        $command->getCommand()->willReturn('CHATMESSAGE');
        $command->getName()->willReturn('name');
        $command->getArgument()->willReturn('not_status');

        $messageHandler->setEngine(Argument::any())->shouldBeCalled();
        $messageHandler->handle(Argument::any(), Argument::any(), Argument::any())->shouldNotBeCalled();

        $this->add($messageHandler);
        $this->handleCommand($command);
    }

    function it_does_not_run_message_handlers_when_command_value_is_not_received(
        SkypeCommandInterface $command, ChatMessageHandlerInterface $messageHandler
    ) {
        $command->getCommand()->willReturn('CHATMESSAGE');
        $command->getName()->willReturn('name');
        $command->getArgument()->willReturn('STATUS');
        $command->getValue()->willReturn('not received');

        $messageHandler->setEngine(Argument::any())->shouldBeCalled();
        $messageHandler->handle(Argument::cetera())->shouldNotBeCalled();

        $this->add($messageHandler);
        $this->handleCommand($command);
    }

    function it_fetches_more_information_from_the_engine_when_a_message_is_received(
        SkypeCommandInterface $command, $engine
    ) {
        $this->setCommandAsReceived($command);

        $engine->invoke('GET CHATMESSAGE name CHATNAME')->shouldBeCalled();
        $engine->invoke('GET CHATMESSAGE name FROM_HANDLE')->shouldBeCalled();
        $engine->invoke('GET CHATMESSAGE name BODY')->shouldBeCalled();
        $engine->invoke('SET CHATMESSAGE name SEEN')->shouldBeCalled();

        $this->handleCommand($command);
    }

    function it_calls_message_handlers_when_a_message_is_received(
        SkypeCommandInterface $command, $engine,  ChatMessageHandlerInterface $messageHandler1,
        ChatMessageHandlerInterface $messageHandler2
    ) {
        $this->setCommandAsReceived($command);

        $messageHandler1->setEngine($engine)->shouldBeCalled();
        $messageHandler2->setEngine($engine)->shouldBeCalled();

        $messageHandler1->handle(
            Argument::type('Inviqa\SkypeCommandInterface'),
            Argument::type('Inviqa\SkypeCommandInterface'),
            Argument::type('Inviqa\SkypeCommandInterface')
        )->shouldBeCalled();

        $messageHandler2->handle(
            Argument::type('Inviqa\SkypeCommandInterface'),
            Argument::type('Inviqa\SkypeCommandInterface'),
            Argument::type('Inviqa\SkypeCommandInterface')
        )->shouldBeCalled();

        $this->add($messageHandler1);
        $this->add($messageHandler2);
        $this->handleCommand($command);
    }

    private function setCommandAsReceived(SkypeCommandInterface $command)
    {
        $command->getCommand()->willReturn('CHATMESSAGE');
        $command->getName()->willReturn('name');
        $command->getArgument()->willReturn('STATUS');
        $command->getValue()->willReturn('RECEIVED');
    }
}
