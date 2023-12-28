<!DOCTYPE HTML>
<html>
    <head>
        <meta charset="utf-8">
        <link rel="stylesheet" href="/dnd/css/book.css">
    </head>
    <body>
        <p><a href="/dnd/the-bees">The Bees</a> > <a href="/dnd/the-bees/materials">Materials</a> ></p>
        <div class="entry-block">
<?php
$matfilename = sprintf("%s.html", $_GET["name"]);
if (!file_exists($matfilename)) {
    echo "<p><b>Material not found</b></p>";
}
else {
    $matfile = fopen($matfilename, "r");
    $html = fread($matfile, filesize($matfilename));
    fclose($matfile);

    echo $html;
}
?>
        </div>
    </body>
</html>
