<?php

namespace spec\Inviqa;


class DbusObjectMock extends \DBusObject
{
    /**
     * Mock invoke method because Prophecy doesn't let us declare methods
     * that don't exist in the object's interface.
     *
     * @param string $message
     * @return null
     */
    public function invoke($message)
    {
        return null;
    }
}
