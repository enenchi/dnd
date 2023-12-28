<?php
require_once($_SERVER['DOCUMENT_ROOT']."/dnd/resources/page/content.php");

$PATH_TO_HERE = [
    [ "rel_href"=>"/dnd", "name" => "TTRPG Stuff" ],
];

$NOT_FOUND_MESSAGE = "Page not found";

// DO NOT EDIT BELOW HTML
?>

<!DOCTYPE HTML>
<html>
    <head>
        <?php include($_SERVER['DOCUMENT_ROOT']."/dnd/resources/page/header.php"); ?>
    </head>
    <body>
        <?php display_nav($PATH_TO_HERE) ?>

        <div class="entry-block">
            <?php diplay_page($_GET["name"], $NOT_FOUND_MESSAGE) ?>
        </div>
    </body>
</html>