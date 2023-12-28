<!DOCTYPE HTML>
<html>
    <head>
        <meta charset="utf-8">
        <link rel="stylesheet" href="/dnd/css/book.css">
    </head>
    <body>
        <p><a href="/dnd/three-friends-and-a-gambler/">The Hunt</a> > <a href="/dnd/three-friends-and-a-gambler/materials">Materials</a> ></p>
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
