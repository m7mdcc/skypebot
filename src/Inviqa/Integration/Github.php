<?php

namespace Inviqa\Integration;

use Inviqa\SkypeEngine;

class Github implements ExternalIntegrationHandler
{
    protected $dbus;

    public function __construct(\DbusObject $dbus)
    {
        $this->dbus = $dbus;
    }

    public function handle($input)
    {
        $this->getRequest($input);

        if (!isset($request['payload'])) {
            return;
        }

        if ($payload = json_decode($_REQUEST['payload'])) {
            $message = array();

            foreach ($payload->commits as $commit) {
                $lines = explode("\n", $commit->message);
                $message[] = '- ' . $lines[0] . ' ('.substr($commit->id, 0, 6).')';
            }

            $info = sprintf(
                "%s pushed to %s at %s/%s.\n%s",
                $payload->head_commit->committer->name,
                $payload->ref,
                $payload->repository->organization,
                $payload->repository->name,
                join("\n", $message)
            );

            $this->dbus->Invoke("CHATMESSAGE {$_REQUEST['id']} $info");
        }
    }

    private function getRequest($input)
    {
        parse_str($input, $request);
        return $request;
    }
}
