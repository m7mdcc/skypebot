<?php

namespace Inviqa;

use Inviqa\Command\CommandHandlerInterface;

class SkypeEngine {
    
    protected $dbus = null;

    protected $handlers = array();

    public function __construct(\DbusObject $dbus)
    {
        $this->dbus = $dbus;
    }

    public function parse($command)
    {
        $skypeCommand = new SkypeCommand($command);
        foreach ($this->handlers as $commandHandler) {
            if($commandHandler->handleCommand($skypeCommand)) {
                break;
            }
        }
    }
    
    public function addCommandHandler(CommandHandlerInterface $commandHandler)
    {
        $commandHandler->setEngine($this);
        $this->handlers[] = $commandHandler;
    }

    public function invoke($str)
    {
        return $this->dbus->Invoke($str);
    }

    protected function showChatInfo($name)
    {
        $githubBase = "http://skypebot.inviqa.com:9001/github.php";
        $jenkinsBase = "http://skypebot.inviqa.com:9001/jenkins.php";
        $this->dbus->Invoke("CHATMESSAGE $name For github integration, add this URL; $githubBase?id=".urlencode($name)." as a commit hook in your github repository.\n\nFor Jenkins Notifications use $jenkinsBase?id=".urlencode($name)."");
    }

    public static function getDbusProxy()
    {
        $proxy = (new \Dbus(\Dbus::BUS_SESSION, true))->createProxy( "com.Skype.API", "/com/Skype", "com.Skype.API");
        $proxy->Invoke( "NAME PHP" );
        $proxy->Invoke( "PROTOCOL 7" );
        return $proxy;
    }
}

