<?php

// Read json file
$filename = sprintf("%s.json", $_GET["name"]);
if (!file_exists($filename)) {
    echo '<p><b>Tracking cannot find creature info</b></p>';
    exit(0);
}
$infofile = fopen($filename, "r");
$creature = json_decode(fread($infofile, filesize($filename)));
fclose($infofile);

// CREATURE_NAME : string
echo sprintf('<script>const %s = "%s";</script>',
    'CREATURE_NAME', $creature->name);

// CREATURE_HP : int
echo sprintf('<script>const %s = %s;</script>',
    'CREATURE_HP', $creature->health->hp);

?>