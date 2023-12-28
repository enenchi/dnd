<?php

/**
 * Echoes a navigation string
 * @param path_to_here An indexed array of associate arrays of the format
 *          [
 *              "rel_href" => "<path relative to previous in array>",
 *              "name" => "<user facing title>"
 *          ]
 */
function display_nav($path_to_here) {
    $href = "";
    $line = "";
    foreach ($path_to_here as $path) {
        $href = $href . $path["rel_href"];
        $line = $line . sprintf("<a href=\"%s\">%s</a> >", $href, $path["name"]);
    }
    echo "<p>" . $line . "</p>";
}

/**
 * Echoes an html page or $not_found_msg if it doesn't exist.
 */
function diplay_page($name, $not_found_msg) {
    // TODO: Should this check if it's in the same directory? 
    $filename = sprintf("%s.html", $name);
    if (!file_exists($filename)) {
        echo "<p><b>" . $not_found_msg . "</b></p>";
    }
    else {
        $file = fopen($filename, "r");
        $html = fread($file, filesize($filename));
        fclose($file);

        echo $html;
    }
}

?>