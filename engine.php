<?php

use Inviqa\Command\ChatMessage\BurritoHandler;
use Inviqa\Command\ChatMessage\CreateHandler;
use Inviqa\Command\ChatMessage\DeployHandler;
use Inviqa\Command\ChatMessage\InfoHandler;
use Inviqa\Command\ChatMessage\MagentoHandler;
use Inviqa\Command\ChatMessage\PingHandler;
use Inviqa\Command\ChatMessage\PlannerHandler;
use Inviqa\Command\ChatMessage\WikiHandler;
use Inviqa\Command\ChatMessage\FrontdoorHandler;
use Inviqa\Command\ChatMessageCommandHandler;
use Inviqa\Command\UserCommandHandler;
use Inviqa\SkypeEngine;

$engine = new SkypeEngine();

$engine->addCommandHandler(new UserCommandHandler());

$chatMessageHandler = new ChatMessageCommandHandler();
$engine->addCommandHandler($chatMessageHandler);

$chatMessageHandler
    ->add(new CreateHandler())
    ->add(new PingHandler())
    ->add(new MagentoHandler())
    ->add(new BurritoHandler())
    ->add(new PlannerHandler())
    ->add(new WikiHandler())
    ->add(new DeployHandler())
    ->add(new FrontdoorHandler())
    ->add(new InfoHandler());

return $engine;
