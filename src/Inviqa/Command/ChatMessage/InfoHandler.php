<?php

namespace Inviqa\Command\ChatMessage;

use Inviqa\SkypeCommandInterface;

class InfoHandler extends AbstractHandler implements ChatMessageHandlerInterface
{
    CONST GITHUB_URL_BASE = 'http://skypebot.inviqa.com:9001/github.php';
    CONST JENKINS_URL_BASE = 'http://skypebot.inviqa.com:9001/jenkins.php';

    public function handle(SkypeCommandInterface $chatname, SkypeCommandInterface $handle, SkypeCommandInterface $body)
    {
        if (!$this->firstWordIs(':info', $body->getValue())) {
            return;
        }

        $encodedChatname = urlencode($chatname->getValue());
        $this->engine->invoke(
            sprintf(
                'CHATMESSAGE %s For github integration, add this URL; %s?id=%s as a commit hook in your github repository.\n\nFor Jenkins Notifications use %s?id=%s',
                $chatname->getValue(),
                self::GITHUB_URL_BASE,
                $encodedChatname,
                self::JENKINS_URL_BASE,
                $encodedChatname
            )
        );
    }
}
