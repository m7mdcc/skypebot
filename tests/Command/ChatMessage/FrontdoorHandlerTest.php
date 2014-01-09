<?php

use Inviqa\Command\ChatMessage\FrontdoorHandler;
use Inviqa\Command\ChatMessage\WikiHandler;

class FrontdoorHandlerTest extends \PHPUnit_Framework_TestCase
{
    public function testHandlerIsSkippedWithoutFrontdoorKeyword()
    {
        $chatnameCommand = $this->getMock('Inviqa\SkypeCommandInterface');
        $chatnameCommand->expects($this->never())
            ->method('getValue');
        
        $handleCommand = $this->getMock('Inviqa\SkypeCommandInterface');
        
        $bodyCommand = $this->getMock('Inviqa\SkypeCommandInterface');
        $bodyCommand->expects($this->once())
            ->method('getValue')
            ->will($this->returnValue('not_frontdoor'));
        
        $engine = $this->getMockBuilder('Inviqa\SkypeEngine')
            ->disableOriginalConstructor()
            ->getMock();
        $engine->expects($this->never())
            ->method('invoke');
        
        $handler = new FrontdoorHandler();
        $handler->setEngine($engine);
        $handler->handle($chatnameCommand, $handleCommand, $bodyCommand);
    }
    
    public function testHandlerSendsResponseForKeyword()
    {
        $chatnameCommand = $this->getMock('Inviqa\SkypeCommandInterface');
        $chatnameCommand->expects($this->once())
            ->method('getValue')
            ->will($this->returnValue('CHATNAME'));
        
        $handleCommand = $this->getMock('Inviqa\SkypeCommandInterface');
        
        $bodyCommand = $this->getMock('Inviqa\SkypeCommandInterface');
        $bodyCommand->expects($this->any())
            ->method('getValue')
            ->will($this->returnValue(':frontdoor'));
        
        $engine = $this->getMockBuilder('Inviqa\SkypeEngine')
            ->disableOriginalConstructor()
            ->getMock();
        $engine->expects($this->once())
            ->method('invoke')
            ->with('CHATMESSAGE CHATNAME ' . WikiHandler::WIKI_URL . '/VPN+Service#VPNService-Dynamicportredirection');
        
        $handler = new FrontdoorHandler();
        $handler->setEngine($engine);
        $handler->handle($chatnameCommand, $handleCommand, $bodyCommand);
    }
}
