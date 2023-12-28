<?php

require_once($_SERVER['DOCUMENT_ROOT']."/dnd/resources/page/content.php");

$PATH_TO_HERE = [
    [ "rel_href"=>"/dnd/three-friends-and-a-gambler", "name" => "The Hunt" ],
    [ "rel_href"=>"/traits", "name" => "Traits" ],
];

?>

<!DOCTYPE HTML>
<html>
    <head>
        <?php include($_SERVER['DOCUMENT_ROOT']."/dnd/resources/page/header.php"); ?>
    </head>
    <body>
        <?php display_nav($PATH_TO_HERE) ?>

        <div class="entry-block">
<?php

$traitfilename = sprintf("%s.json", $_GET["name"]);
$descfilename = sprintf("%s.html", $_GET["name"]);

if (!file_exists($traitfilename) or !file_exists($descfilename)) {
    echo "<h1>" . $_GET["name"] . "</h1>";
    echo "<p><b>Item not found</b></p>";
    exit;
}

$traitfile = fopen($traitfilename, "r");
$trait = json_decode(fread($traitfile, filesize($traitfilename)));
fclose($traitfile);

$descfile = fopen($descfilename, "r");
$desc = fread($descfile, filesize($descfilename));
fclose($descfile);

echo "<h1>" . $trait->name . "</h1>";

echo $desc;

?>
        </div>
    </body>
</html>
