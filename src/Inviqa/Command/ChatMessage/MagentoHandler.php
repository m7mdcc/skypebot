<?php

namespace Inviqa\Command\ChatMessage;

use Inviqa\SkypeCommandInterface;

class MagentoHandler extends AbstractHandler implements ChatMessageHandlerInterface
{
    protected $suggestions = array(
        'Have you tried clearing the cache?',
        'Have you tried reindexing?',
        'Have you tried restarting memcache or clearing redis?',
        'Has Magento fallen back to filesystem cache in the system /tmp directory?',
        'Is it a case-sensitivity issue?',
        'Is there a conflicting module over-ride?',
        'Is the layout XML picking up the correct block?',
        'Is the template getting the right data from the block?',
        'Is a store-scope configuration setting over-riding the default setting?',
        'Are you really checking the same environment as you are making changes on?',
        'Have you sacrificed a chicken?',
    );

    public function handle(SkypeCommandInterface $chatname, SkypeCommandInterface $handle, SkypeCommandInterface $body)
    {
        if (!$this->firstWordIs(':magento', $body->getValue())) {
            return;
        }

        $suggestion = $this->suggestions[array_rand($this->suggestions)];
        $this->engine->invoke("CHATMESSAGE {$chatname->getValue()} $suggestion");
    }
}
