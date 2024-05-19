<?php

require_once($_SERVER['DOCUMENT_ROOT']."/dnd/resources/page/content.php");

$PATH_TO_HERE = [
    [ "rel_href"=>"/dnd/three-friends-and-a-gambler", "name" => "The Hunt" ],
    [ "rel_href"=>"/systems", "name" => "Alt. Rules and Systems" ],
];

?>

<!DOCTYPE HTML>
<html>
    <head>
        <?php include($_SERVER['DOCUMENT_ROOT']."/dnd/three-friends-and-a-gambler/resources/page/header.php"); ?>
    </head>
    <body>
        <?php display_nav($PATH_TO_HERE) ?>

        <div class="entry-block">
<?php

$filename = sprintf("%s.html", $_GET["name"]);

if (!file_exists($filename)) {
    echo "<h1>" . $_GET["name"] . "</h1>";
    echo "<p><b>Page not found</b></p>";
    exit;
}

$file = fopen($filename, "r");
$content = fread($file, filesize($filename));
fclose($file);

echo $content;

?>
        </div>
    </body>
</html>
