<?php
require_once '../vendor/autoload.php';
use Inviqa\SkypeEngine;

$githubHandler = new GithubHandler();
$githubHandler->handleRequest(file_get_contents('php://input'));

class GithubHandler
{
    public function handleRequest($input)
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

            SkypeEngine::getDbusProxy()->Invoke( "CHATMESSAGE {$_REQUEST['id']} $info");
        }
    }

    private function getRequest($input)
    {
        parse_str($input, $request);
        return $request;
    }
}
