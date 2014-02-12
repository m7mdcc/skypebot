<?php

namespace Inviqa\Command\ChatMessage;

use Inviqa\SkypeCommandInterface;

class WikiHandler extends AbstractHandler implements ChatMessageHandlerInterface
{
    const WIKI_URL = 'https://ibuildings.jira.com/wiki/label/PROFSERV';

    public function handle(SkypeCommandInterface $chatname, SkypeCommandInterface $handle, SkypeCommandInterface $body)
    {
        if (!$this->firstWordIs(':wiki', $body->getValue())) {
            return;
        }

        $args = explode(' ', $body->getValue());

        if (!isset($args[1])) {
            $this->engine->invoke(
                "CHATMESSAGE {$chatname->getValue()} You must supply a wiki name, e.g. :wiki Incubator"
            );
            return;
        }

        $this->engine->invoke(
            sprintf(
                'CHATMESSAGE %s %s/%s',
                $chatname->getValue(),
                self::WIKI_URL,
                $args[1]
            )
        );
    }
}
