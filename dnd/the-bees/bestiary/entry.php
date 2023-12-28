<!DOCTYPE HTML>
<html>
<head>
    <meta charset="utf-8">
    <link rel="stylesheet" href="/dnd/css/book.css">
    <link rel="stylesheet" href="/dnd/css/bestiary.css">
</head>
<body>
<p><a href="../../the-bees">The Bees</a> > <a href="../bestiary">Bestiary</a> ></p>
<?php
$filename = sprintf('%s.html', $_GET['name']);
if (file_exists($filename)) {
    $htmlfile = fopen($filename, "r");
    echo fread($htmlfile, filesize($filename));
    fclose($htmlfile);
    exit(0);
}
$filename = sprintf('%s.creature.json', $_GET['name']);
if (file_exists($filename)) {
    echo file_get_contents('http://localhost/dnd/the-bees/bestiary/creature-entry.php?name=' . $_GET['name']);
    exit(0);
}
$filename = sprintf('%s.player.json', $_GET['name']);
if (file_exists($filename)) {
    echo file_get_contents('http://localhost/dnd/the-bees/bestiary/player-entry.php?name=' . $_GET['name']);
    exit(0);
}
echo sprintf('<p><b>%s not found.</b></p>', $_GET['name']);

?>
</body>
</html>
