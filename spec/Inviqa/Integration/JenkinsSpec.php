<?php

namespace spec\Inviqa\Integration;

require_once(__DIR__ . '/../DbusObjectMock.php');

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use spec\Inviqa\DbusObjectMock;

class JenkinsSpec extends ObjectBehavior
{
    function let(DbusObjectMock $dbus)
    {
        $this->beConstructedWith($dbus);
    }

    function it_is_an_external_integration_handler()
    {
        $this->shouldImplement('Inviqa\Integration\ExternalIntegrationHandler');
    }

    function it_does_not_respond_to_an_empty_string($dbus)
    {
        $dbus->invoke(Argument::any())->shouldNotBeCalled();
        $this->handle('');
    }

    function it_responds_to_a_build_starting($dbus)
    {
        $_REQUEST['id'] = 'ID';
        $payload = '{"name": "test-app", "build": {"phase": "STARTED", "number": "3", "full_url": "http://some-url.com"}}';
        $dbus->invoke('CHATMESSAGE ID test-app build 3 has started at http://some-url.com.')->shouldBeCalled();
        $this->handle($payload);
    }

    function it_responds_to_a_build_finishing($dbus)
    {
        $_REQUEST['id'] = 'ID';
        $payload = '{"name": "test-app", "build": {"phase": "FINISHED", "status": "fubar", "number": "3", "full_url": "http://some-url.com"}}';
        $dbus->invoke(
            'CHATMESSAGE ID test-app build number 3 finished with status fubar at http://some-url.com.'
        )->shouldBeCalled();
        $this->handle($payload);
    }

    function it_ignores_unrecognised_phases($dbus)
    {
        $payload = '{"name": "test-app", "build": {"phase": "NON-PHASE"}}';
        $dbus->invoke(Argument::any())->shouldNotBeCalled();
        $this->handle($payload);
    }
}


