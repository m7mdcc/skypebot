<?php

namespace Inviqa\Command\ChatMessage;

use Inviqa\SkypeCommandInterface;

class DeployHandler extends AbstractHandler implements ChatMessageHandlerInterface
{
    public function handle(SkypeCommandInterface $chatname, SkypeCommandInterface $handle, SkypeCommandInterface $body)
    {
        if (!$this->firstWordIs(':deploy', $body->getValue())) {
            return;
        }
        
        $dir = '/var/lib/bot';

        $roomgit = array(
            '#blongden.inviqa/$dhowlett.inviqa;66b8c356c0d9e779'   => 'git@github.com:PZCussons/drupal.git',
            '#grogers.inviqa/$gkemp.inviqa;18662fe1c4c1b330'       => 'git@github.com:PZCussons/drupal.git',
            '#blongden.inviqa/$jenkins-incubator;1d56633d322bb7fc' => 'git@github.com:PZCussons/drupal.git'
        );

        if (!array_key_exists($chatname->getValue(), $roomgit)) {
            $this->engine->invoke("CHATMESSAGE {$chatname->getValue()} I don't know about any deployments for this skype room.");
            return false;
        }
        $output = array();
        preg_match('/(?P<user>\w+)@(?P<host>[^:]+):(?P<path>.+)/', $roomgit[$chatname->getValue()], $url);
        $parent = explode('/', $url['path']);
        if (!file_exists("$dir/{$parent[0]}")) {
            mkdir("$dir/{$parent[0]}");
        }
        if (!file_exists("$dir/{$url['path']}")) {
            chdir($dir);
            exec("git clone {$roomgit[$chatname->getValue()]} {$url['path']} 2>&1", $output);
            chdir("$dir/{$url['path']}");
        } else {
            chdir("$dir/{$url['path']}");
            exec("git pull origin master 2>&1", $output);
        }
        chdir("$dir/{$url['path']}/tools/capistrano");

        $this->engine->invoke("CHATMESSAGE {$chatname->getValue()} I am deploying {$url['path']} to the development stage.");
        exec("cap deploy 2>&1", $output);
        $filename = uniqid();
        $log = "logs/$filename.txt";
        file_put_contents(__DIR__."/$log", implode("\n", $output));
        $this->engine->invoke("CHATMESSAGE {$chatname->getValue()} Deployment complete - check http://skypebot.inviqa.com:9001/$log for details.");
    }
}
