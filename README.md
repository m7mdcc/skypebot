# Skypebot

[![Build Status](https://travis-ci.org/inviqa/skypebot.png?branch=master)](https://travis-ci.org/inviqa/skypebot)

## Description

A PHP backend for controlling a Skype user via a DBus connection to a running Skype client.

## Running the bot

Steps required to run the bot:

1. Install the PECL DBus extension (see below)
1. Install the Skype client and sign in
1. From a terminal, run the dbus monitor script `php dbus-monitor.php`

On the first run, the Skype client application should show a popup that requires you to authorise the Dbus script.

## Installing DBus

The PHP dbus libraries can be installed from PECL:

    sudo pecl install DBus-0.1.1

Further libraries my be required for your operation system.

- [Install PHP DBus on Ubuntu](http://web-dev-wiki.blogspot.co.uk/2012/11/how-to-install-dbus-for-php-on-ubuntu.html)

## Handling incoming commands

When a command is recieved, the main engine class runs though a list of added command handler ojects until it finds one that is prepared to handle the command. An example of this is Inviqa\Command\UserCommandHandler, which attempts to handle any commands that start with the `USER` keyword.

Command handlers are added to the Engine class in engine.php:

    $engine = new SkypeEngine($n);
    $engine->addCommandHandler(new UserCommandHandler());

The order in which commands are added decided the order in which they are given an oppertunity to handle each request. The engine will call the `handle()` method of each handler until one returns _true_, at which point it will stop.

## Handling chat messages

There is a command handler provided for handling the `CHATMESSAGE` command type. This handler takes the body of every chat message recieved, and offers it to every registered handler in turn. Each handler has the chance to act on the incoming message, so a single mesage and trigger several different responses.

Chat message handler objects should be added the the ChatMessageHandler **after** it has itself been added to the main Engine in engine.php:

    $engine = new SkypeEngine($n);
    $chatMessageHandler = new ChatMessageCommandHandler();
    $engine->addCommandHandler($chatMessageHandler);
    $chatMessageHandler->add(new PingHandler())

## Contributing

The Skypebot is an open source project, and pull requests are welcome. Ideally, bug fixes and new features should be supported by unit tests, and PSR-2 coding style is preferred.

## License and Author

   Author:: Ben Longden (blongden@inviqa.com)

   Copyright 2012, Inviqa

   Licensed under the Apache License, Version 2.0 (the "License");
   you may not use this file except in compliance with the License.
   You may obtain a copy of the License at

   [http://www.apache.org/licenses/LICENSE-2.0](http://www.apache.org/licenses/LICENSE-2.0)

   Unless required by applicable law or agreed to in writing, software
   distributed under the License is distributed on an "AS IS" BASIS,
   WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
   See the License for the specific language governing permissions and
   limitations under the License.
