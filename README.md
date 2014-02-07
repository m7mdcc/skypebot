# Skypebot

[![Build Status](https://travis-ci.org/inviqa/skypebot.png?branch=master)](https://travis-ci.org/inviqa/skypebot)

## Description

A PHP backend for controlling a Skype user via a DBus connection to a running Skype client.

The skypebot will run almost anywhere that supports DBus, PHP5.4 and the Skype client application. An Ubuntu 12.10 VM is provided for ease fo development.

## Development VM

An Ubuntu 12.10, Vagrant/Chef driven VM is provided. To get started you must first ensure that you have the following installed:

- Vagrant > 2.0
- Librarian-chef

### Setting up the VM

The steps to starting up the VM are:

    # Install chef cookbooks
    cd tools/chef
    librarian-chef install
    # Start the VM
    cd ../vagrant
    vagrant up --provision
    
The VM will present VirtualBox in GUI mode. It is recommended to not sign in until the provisioning is complete.

### Using the VM

Sign in with the credential. vagrant:vagrant

The VM comes with PHP5.4, Skype and DBus preinstalled, and the bot source is mounted at `~/skypebot`. 

To use the bot, first open the Skype application. If required, you should create a new account to be your bot. )Once Skype is running, open a terminal and type:

    cd skypebot
    php dbus-monitor.php
    
On the first run, the Skype client application should show a popup that requires you to authorise the Dbus script.

You can then install th Skype client application on your host machine, and using a different Skype account communicate with the bot inside your VM.

## Installing locally

### Running the bot

Steps required to run the bot locally:

1. Install the PECL DBus extension (see below)
1. Install the Skype client and sign in
1. From a terminal, run the dbus monitor script `php dbus-monitor.php`

On the first run, the Skype client application should show a popup that requires you to authorise the Dbus script.

### Installing DBus

The PHP dbus libraries can be installed from PECL:

    sudo pecl install DBus-0.1.1

Further libraries my be required for your operating system.

- [Install PHP DBus on Ubuntu](http://web-dev-wiki.blogspot.co.uk/2012/11/how-to-install-dbus-for-php-on-ubuntu.html)

## The skypebot

### Handling incoming commands

When a command is recieved, the main engine class runs though a list of added command handler ojects until it finds one that is prepared to handle the command. An example of this is Inviqa\Command\UserCommandHandler, which attempts to handle any commands that start with the `USER` keyword.

Command handlers are added to the Engine class in engine.php:

    $engine = new SkypeEngine($n);
    $engine->addCommandHandler(new UserCommandHandler());

The order in which commands are added decided the order in which they are given an oppertunity to handle each request. The engine will call the `handle()` method of each handler until one returns _true_, at which point it will stop.

### Handling chat messages

There is a command handler provided for handling the `CHATMESSAGE` command type. This handler takes the body of every chat message recieved, and offers it to every registered handler in turn. Each handler has the chance to act on the incoming message, so a single mesage and trigger several different responses.

Chat message handler objects should be added the the ChatMessageHandler **after** it has itself been added to the main Engine in engine.php:

    $engine = new SkypeEngine($n);
    $chatMessageHandler = new ChatMessageCommandHandler();
    $engine->addCommandHandler($chatMessageHandler);
    $chatMessageHandler->add(new PingHandler())

### Contributing

The Skypebot is an open source project, and pull requests are welcome. Ideally, bug fixes and new features should be supported by unit tests, and PSR-2 coding style is preferred.

### License and Author

   Author:: Ben Longden (blongden@inviqa.com)
   Author:: Shane Auckland (sauckland@inviqa.com)

   Copyright 2014, Inviqa

   Licensed under the Apache License, Version 2.0 (the "License");
   you may not use this file except in compliance with the License.
   You may obtain a copy of the License at

   [http://www.apache.org/licenses/LICENSE-2.0](http://www.apache.org/licenses/LICENSE-2.0)

   Unless required by applicable law or agreed to in writing, software
   distributed under the License is distributed on an "AS IS" BASIS,
   WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
   See the License for the specific language governing permissions and
   limitations under the License.
