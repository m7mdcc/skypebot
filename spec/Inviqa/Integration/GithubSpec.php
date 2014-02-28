<?php

namespace spec\Inviqa\Integration;

require_once(__DIR__ . '/../DbusObjectMock.php');;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use spec\Inviqa\DbusObjectMock;

class GithubSpec extends ObjectBehavior
{
    function let(DbusObjectMock $dbus)
    {
        $this->beConstructedWith($dbus);
    }

    public function it_is_an_external_integration_handler()
    {
        $this->shouldImplement('Inviqa\Integration\ExternalIntegrationHandler');
    }

    function it_does_not_respond_to_an_empty_string($dbus)
    {
        $dbus->invoke(Argument::any())->shouldNotBeCalled();
        $this->handle('');
    }

    function it_responds_to_a_commit_in_a_payload($dbus)
    {
        $request = 'id=req-id&payload={"commits":[{"id": "123456", "message": "commit-message"}], "ref": "ref-hash", "repository": {"name": "repo", "organization": "inviqa"}, "head_commit": {"committer": {"name": "me"}}}';
        $dbus->invoke(
            "CHATMESSAGE req-id me pushed to ref-hash at inviqa/repo.\n- commit-message (123456)"
        )->shouldBeCalled();
        $this->handle($request);
    }

    function it_truncates_the_commit_id_to_six_characters($dbus)
    {
        $request = 'id=req-id&payload={"commits":[{"id": "123456789", "message": "commit-message"}], "ref": "ref-hash", "repository": {"name": "repo", "organization": "inviqa"}, "head_commit": {"committer": {"name": "me"}}}';
        $dbus->invoke(
            "CHATMESSAGE req-id me pushed to ref-hash at inviqa/repo.\n- commit-message (123456)"
        )->shouldBeCalled();
        $this->handle($request);
    }

    function it_outputs_multiple_commits($dbus)
    {
        $request = 'id=req-id&payload={"commits":[{"id": "123456", "message": "commit1"}, {"id": "789abc", "message": "commit2"}], "ref": "ref-hash", "repository": {"name": "repo", "organization": "inviqa"}, "head_commit": {"committer": {"name": "me"}}}';
        $expected = "CHATMESSAGE req-id me pushed to ref-hash at inviqa/repo.\n- commit1 (123456)\n- commit2 (789abc)";
        $dbus->invoke($expected)->shouldBeCalled();
        $this->handle($request);
    }

    function it_only_outputs_the_first_line_of_the_commit_message($dbus)
    {
        $request = 'id=req-id&payload={"commits":[{"id": "123456789", "message": "commit-message\nline2"}], "ref": "ref-hash", "repository": {"name": "repo", "organization": "inviqa"}, "head_commit": {"committer": {"name": "me"}}}';
        $dbus->invoke(
            "CHATMESSAGE req-id me pushed to ref-hash at inviqa/repo.\n- commit-message (123456)"
        )->shouldBeCalled();
        $this->handle($request);
    }
}
