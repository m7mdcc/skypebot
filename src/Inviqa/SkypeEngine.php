<?php

namespace Inviqa;

use Inviqa\Command\CommandHandlerInterface;

class SkypeEngine
{
    protected static $engineInstance;

    protected $dbus;

    protected $handlers = array();

    public function __construct(\DbusObject $dbus = null)
    {
        $this->setDbusObject($dbus);
    }

    public function setDbusObject(\DbusObject $dbus = null)
    {
        $this->dbus = $dbus;
    }

    public function parse($command)
    {
        $skypeCommand = new SkypeCommand($command);
        foreach ($this->handlers as $commandHandler) {
            if ($commandHandler->handleCommand($skypeCommand)) {
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

    public static function getDbusProxy()
    {
        $proxy = (new \Dbus(\Dbus::BUS_SESSION, true))->createProxy('com.Skype.API', '/com/Skype', 'com.Skype.API');
        $proxy->Invoke('NAME PHP');
        $proxy->Invoke('PROTOCOL 7');
        return $proxy;
    }

    public static function setEngineInstance(self $engine)
    {
        self::$engineInstance = $engine;
    }

    public static function notify($str)
    {
        try {
            self::$engineInstance->parse($str);
        } catch (\Exception $exception) {
            echo $exception->getMessage().PHP_EOL;
        }
    }
}
