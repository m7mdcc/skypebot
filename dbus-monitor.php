<?php
require_once 'vendor/autoload.php';

class SkypeBot {
    static function notify($a) {
        global $engine;
        try {
            $engine->parse($a);
        } catch (Exception $e) {
            echo $e->getMessage().PHP_EOL;
        }
    }
}

$d = new Dbus( Dbus::BUS_SESSION, true );
$success = false;
$n = $d->createProxy( "com.Skype.API", "/com/Skype", "com.Skype.API");
$engine = require_once('engine.php');
do {
	try {
		$n->Invoke( "NAME PHP" );
		$success = true;
	} catch (Exception $e) {
		print $e->getMessage().PHP_EOL;
		sleep(1);
	}
} while (!$success);

$n->Invoke( "PROTOCOL 7" );
$d->registerObject( '/com/Skype/Client', 'com.Skype.API.Client', 'SkypeBot' );

echo "Entering wait loop".PHP_EOL;

$x = 0;
do {
    $s = $d->waitLoop( 1000 );
    $x++;
}
while ( $x < 10000 );
