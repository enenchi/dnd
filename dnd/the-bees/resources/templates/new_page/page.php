<!DOCTYPE HTML>
<html>
    <head>
        <meta charset="utf-8">
        <link rel="stylesheet" href="/dnd/css/book.css">
    </head>
    <body>
        <p><a href="/dnd/the-bees">The Bees</a> > <a href="/dnd/the-bees/<variable parentPath>"><variable parentName></a> ></p>
        <div class="entry-block">
<?php
$filename = sprintf("%s.html", $_GET["name"]);
if (!file_exists($filename)) {
    echo "<p><b>Page not found</b></p>";
}
else {
    $file = fopen($filename, "r");
    $html = fread($file, filesize($filename));
    fclose($file);

    echo $html;
}
?>
        </div>
    </body>
</html>
