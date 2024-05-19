<?php

require_once($_SERVER['DOCUMENT_ROOT']."/dnd/resources/page/content.php");

$PATH_TO_HERE = [
    [ "rel_href"=>"/dnd/three-friends-and-a-gambler", "name" => "The Hunt" ],
    [ "rel_href"=>"/bestiary", "name" => "Bestiary" ],
];

?>

<!DOCTYPE HTML>
<html>
    <head>
        <?php include($_SERVER['DOCUMENT_ROOT']."/dnd/three-friends-and-a-gambler/resources/page/header.php"); ?>
        <link rel="stylesheet" href="/dnd/css/bestiary.css">
    </head>
    <body>
    <?php display_nav($PATH_TO_HERE) ?>

    <div id="creature-tracking">
        <button id="track-button" onclick="startTracking()">Track a creature</button>
        <script src="track-creature.js"></script>
    </div>
    
<?php
require_once('track-creature.php');
require_once('creature-entry.php');
?>
    </body>
</html>
