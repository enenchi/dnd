<?php
$UNKNOWN = '<i>idk</i>';

function formatValue($v, $numFormat='%d') {
    if ($v == null) {
        global $UNKNOWN;
        return $UNKNOWN;
    }
    if (is_numeric($v)) {
        return sprintf($numFormat, $v);
    }
    if (is_array($v)) {
        $ret = formatValue($v[0]);
        for ($i = 1; $i < count($v); $i++) {
            $ret = $ret . ', ' . formatValue($v[$i], $numFormat);
        }
        return $ret;
    }
    return strval($v);
}

function echoJSON($json, $key_cb="ucfirst", $value_cb='formatValue') {
    if (is_object($json)) {
        foreach ($json as $key => $value) {
            $key = call_user_func_array($key_cb, array($key));
            $value = call_user_func_array($value_cb, array($value));
            echo sprintf('<b>%s</b> %s<br>', $key, $value);
        }
    }
    /*
    elseif (is_array($json)) {
        echo call_user_func_array($value_cb, array($json));i
    }
    else {
        echo call_user_func_array($value_cb, array($json));
    }
     */
}
?>
<?php
$filename = sprintf("%s.player.json", $_GET["name"]);
if (!file_exists($filename)) {
    echo sprintf("<p><b>%s not found.</b></p>", $_GET["name"]);
    exit(0);
}
$infofile = fopen($filename, "r");
$creature = json_decode(fread($infofile, filesize($filename)));
fclose($infofile);

$BEES_URL = "http://localhost/dnd/the-bees/";

echo sprintf("<h1>%s</h1>", $creature->name);

echo "<div class=\"bestiary-entry\">";

echo "<div class=\"statblock bestiary-entry-child\">";

// initial section
echo sprintf('<p class="title">%s</p>', $creature->name);

echo '<p>';
echoJSON($creature->summary);
echo "</p>";

// defense section
echo "<p class=\"divider\">DEFENSE</p>";
echo "<p>";
echo  sprintf('<b>AC</b> %s,' .
    ' <a href="https://www.d20pfsrd.com/gamemastering/combat#TOC-Touch-Attacks">touch</a> %s,' . 
    ' <a href="https://www.d20pfsrd.com/gamemastering/conditions#TOC-Flat-Footed">flat-footed</a> %s<br>',
    formatValue($creature->defense->acs->ac),
    formatValue($creature->defense->acs->touch),
    formatValue($creature->defense->acs->{'flat-footed'}));

echo sprintf('<b>hp</b> %s<br>', formatValue($creature->defense->hp->hp));
echo sprintf('<b>HD</b> %s<br>', formatValue($creature->defense->hp->hd));
echo sprintf('<b>Fort</b> %s, <b>Ref</b> %s, <b>Will</b> %s <br>',
    formatValue($creature->defense->saves->fort, '%+d'),
    formatValue($creature->defense->saves->ref, '%+d'),
    formatValue($creature->defense->saves->will, '%+d'));

echo sprintf('<b>Fast Healing</b> %s<br>', formatValue($creature->defense->{"fast healing"}));
echo sprintf('<b>DR</b> %s<br>', formatValue($creature->defense->dr));
echo sprintf('<b>Immune</b> %s<br>', formatValue($creature->defense->immune));
echo sprintf('<b>Weaknesses</b> %s<br>', formatValue($creature->defense->weaknesses));
echo '</p>';

// offense section
echo '<p class="divider">OFFENSE</p>';
echo '<p>';
echo '<b>Speed</b>';
$first = true;
foreach ($creature->offense->speeds as $movement) {
    if (!$first) {
        echo ",";
    }
    else {
        $first = false;
    }
    if ($movement->type) {
        echo sprintf(' %s %s%s',
            $movement->type,
            ($movement->speed == null) ? $UNKNOWN : $movement->speed,
            ($movement->unit == null) ? '' : ' ' . $movement->unit);
    }
    else {
        echo sprintf(' %s%s',
            ($movement->speed == null) ? $UNKNOWN : $movement->speed,
            ($movement->unit == null) ? '' : ' ' . $movement->unit,);
    }
}
echo '<br>';
echoJSON($creature->offense->attacks);
echo '</p>';

// notes section
echo '<p class="divider">NOTES</p>';
echo '<ul>';
foreach ($creature->notes as $note) {
    echo sprintf('<li>%s</li>', $note);
}
echo '</ul>';
echo '</p>';
/*
// Statistics section
echo '<p class="divider">STATISTICS</p>';
echo '<p>';
$first = true;
foreach ($creature->abilityScores as $ab => $val) {
    if (!$first) {
        echo ', ';
    }
    else {
        $first = false;
    }
    echo sprintf('<b>%s</b> %d', ucfirst($ab), $val);
}
echo '<br>';
echo sprintf('<b>Base Atk</b> %+d; <b>CMB</b> %+d; <b>CMD</b> %d<br>',
    $creature->bab, $creature->cmb, $creature->cmd);
if (count($creature->feats) > 0) {
    echo '<b>Feats</b> ';
    $feat = $creature->feats[0];
    $url = file_get_contents($BEES_URL.'/pathfinder-link.php'.sprintf(
        '?type=feat&name=%s&featType=%s',  str_replace(' ', '-', $feat->name), $feat->type));
    echo sprintf('<a href="%s">%s</a>%s', $url, $feat->name,
        ($feat->modifier == null) ? '' : ' ' . $feat->modifier);
    for ($i = 1; $i < count($creature->feats); $i++) {
        $feat = $creature->feats[$i];
        $url = file_get_contents($BEES_URL.'/pathfinder-link.php'.sprintf(
            '?type=feat&name=%s&featType=%s', str_replace(' ', '-', $feat->name), $feat->type));
        echo sprintf(', <a href="%s">%s</a>%s', $url, $feat->name,
            ($feat->modifier == null) ? '' : ' ' . $feat->modifier);
    }
}
echo '<br>';
echo '<b>Skills</b> ';
if (count($creature->skills) > 0) {
    $skill = $creature->skills[0];
    echo sprintf('<a href="%s">%s</a> %+d', file_get_contents(
        $BEES_URL.'/pathfinder-link.php'.sprintf(
            '?type=skill&name=%s',$skill->name)), $skill->name, $skill->value);
    for ($i = 1; $i < count($creature->skills); $i++) {
        $skill = $creature->skills[$i];
        echo sprintf(', <a href="%s">%s</a> %+d', file_get_contents(
            $BEES_URL.'/pathfinder-link.php'.sprintf(
                '?type=skill&name=%s',$skill->name)), $skill->name, $skill->value);
    }
}
*/
?>
