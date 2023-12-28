<?php

require_once("http://localhost/dnd/the-bees/resources/constants.php");
require_once($REFERENCES_URL);

$filename = sprintf("%s.creature.json", $_GET["name"]);
if (!file_exists($filename)) {
    echo sprintf("<p><b>%s not found.</b></p>", $_GET["name"]);
    exit(0);
}
$infofile = fopen($filename, "r");
$info = json_decode(fread($infofile, filesize($filename)));
fclose($infofile);

$creature = $info->creature;

$xp = get_total_xp_reward($creature->cr);

echo sprintf("<h1>%s</h1>", $creature->name);

echo "<div class=\"bestiary-entry\">";

echo "<div class=\"statblock bestiary-entry-child\">";

// initial section
echo sprintf("<p class=\"title\">%s <span class=\"level\">CR %s</span></p>",
    $creature->name, $creature->cr);
echo "<p>";
echo sprintf('<b>XP %d</b>', $xp);
echo sprintf('<br> %s %s <a href="https://www.d20pfsrd.com/bestiary/rules-for-monsters/creature-types#%s">%s</a><br>',
    $creature->alignment, $creature->size, ucfirst($creature->type), $creature->type);
echo sprintf('<b>Init</b> %+d; <b>Senses</b> ', $creature->init);

$first = true;
foreach($creature->senses as $sense => $value) {
    if (!$first) {
        echo ', ';
    }
    else {
        $first = false;
    }
    echo sprintf('<a href="%s">%s</a> %+d', file_get_contents(
        $PATHFINDER_LINK_URL.sprintf('?type=skill&name=%s', $sense)), $sense, $value);
}
echo "</p>";

// defense section
echo "<p class=\"divider\">DEFENSE</p>";
echo "<p>";

echo  sprintf('<b>AC</b> %d, <a href="https://www.d20pfsrd.com/gamemastering/combat#TOC-Touch-Attacks">touch</a> %d,' . 
    ' <a href="https://www.d20pfsrd.com/gamemastering/conditions#TOC-Flat-Footed">flat-footed</a> %d',
    $creature->ac->ac, $creature->ac->touch, $creature->ac->{'flat-footed'});

echo sprintf(' (%+d <a href="https://www.d20pfsrd.com/basics-ability-scores/ability-scores#TOC-Dexterity-Dex-">Dex</a>',
    floor(($creature->abilityScores->dex - 10) / 2));
foreach ($creature->ac->src as $src) {
    $url = file_get_contents($PATHFINDER_LINK_URL.sprintf('?type=ac&name=%s', $src->name));
    echo sprintf(', %+d <a href="%s">%s</a>', $src->value, $url, $src->name);
}
echo ')<br>';

echo sprintf('<b>hp</b> %d (%dd%d%+d)<br>', $creature->health->hp,
    $creature->health->hd, $creature->health->die,
    floor(($creature->abilityScores->con - 10) / 2) * $creature->health->hd);
echo sprintf('<b>Fort</b> %+d, <b>Ref</b> %+d, <b>Will</b> %+d <br>',
    $creature->saves->fort, $creature->saves->ref, $creature->saves->will);
echo '</p>';

// offernce section
echo '<p class="divider">OFFENSE</p>';
echo '<p>';
echo '<b>Speed</b>';
if (count($creature->speeds) > 0) {
    $movement = $creature->speeds[0];
    echo sprintf(' %s%s %s%s', ($movement->type == null) ? '' : $movement->type . ' ',
        $movement->speed, $movement->unit,
        ($movement->modifier == null) ? '' : ' ' . $movement->modifier);
}
for ($i = 1; $i < count($creature->speeds); $i++) {
    $movement = $creature->speeds[$i];
    echo sprintf(', %s%s %s%s', ($movement->type == null) ? '' : $movement->type . ' ',
        $movement->speed, $movement->unit,
        ($movement->modifier == null) ? '' : ' ' . $movement->modifier);
}
echo '<br>';
foreach ($creature->attacks as $attackType => $attackList) {
    echo sprintf('<b>%s</b>', ucwords($attackType));
    echo ' ' . $attackList[0];
    for ($i = 1; $i < count($attackList); $i++) {
        echo ', ' . $attackList[$i];
    }
    echo '<br>';
}

// Statistics section
echo '</p>';
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
/*
<a href="https://www.d20pfsrd.com/feats/combat-feats/weapon-finesse-combat">Weapon Finesse</a>, <a href="https://www.d20pfsrd.com/feats/monster-feats/ability-focus">Ability Focus</a> (musk) <br>
                    <b>Skills</b> <a href="https://www.d20pfsrd.com/skills/fly">Fly</a> +8, <a href="https://www.d20pfsrd.com/skills/perception">Perception</a> +8, <a href="https://www.d20pfsrd.com/skills/swim/">Swim</a> +8, <a href="https://www.d20pfsrd.com/skills/survival/">Survival</a> +2; Racial Modifiers</b> +8 <a href="https://www.d20pfsrd.com/skills/perception">Perception</a>, +6 <a href="https://www.d20pfsrd.com/skills/swim/">Swim</a><br>
                </p>
                <p class="divider">SPECIAL ABILITIES</p>
                <p class="title">Musk (Ex)</p>
                <p>Up to twice per day, a smellican can spray a stream of noxious musk at a single target within 20 feet as a <a href="https://www.d20pfsrd.com/gamemastering/combat#TOC-Standard-Actions">standard action</a>. With a successful ranged <a href="https://www.d20pfsrd.com/gamemastering/combat#TOC-Touch-Attacks">touch attack</a>, the creature struck by this spray must make a DC 16 <a href="https://www.d20pfsrd.com/gamemastering/combat#TOC-Fortitude">Fortitude</a> save or be <a href="https://www.d20pfsrd.com/gamemastering/conditions#TOC-Nauseated">nauseated</a> for 1d4 rounds and then <a href="https://www.d20pfsrd.com/gamemastering/conditions#TOC-Sickened">sickened</a> for 1d4 minutes by the horrific stench. A successful save reduces the effect to only 1d4 rounds of being <a href="https://www.d20pfsrd.com/gamemastering/conditions#TOC-Sickened">sickened</a>. A creature cannot use the <a href="https://www.d20pfsrd.com/bestiary/rules-for-monsters/universal-monster-rules#TOC-Scent-Ex-">scent</a> ability as long as it is affected by this musk. The save DC is <a href="https://d20pfsrd.com/basics-ability-scores/ability-scores#TOC-Constitution-Con-">Constitution</a>-based, and includes a +2 <a href="https://www.d20pfsrd.com/basics-ability-scores/glossary#TOC-Racial-Bonus">racial bonus</a>.</p>
            </div>
            <div class="ecology bestiary-entry-child">
                <p class="divider">ECOLOGY</p>
                <p>
                    <b>Environment</b> warm beaches, temperate forests <br>
                    <b>Organization</b> solitary, pair, or colony (3-20) <br>
                    <b>Treasure</b> none
                </p>
                <p>
                    Smellicans are characterized by a long beak, a large throat pouch used for catching prey and draining water from scooped-up contents before swallowing, their ability to spray a very unpleasant-smelling liquid from their butt, and distinctive striped markings. <br> <br>
                    They have moderatedly enlongated bodies with relatively short, well-muscled legs and long front claws for digging. They have five toes on each front foot and webbed back feet. They have a long neck with a tiny tongue to avoid hindering swallowing bigger fish. Their wings are long and broad, suitably shaped for soaring and gliding flight. A smellican's coat is made up of very densely packed hairs and functions to keep the animal warm, help it float in water, and protect it against the teeth and claws of predators.<br> <br>
                    Their fur coloration varies in appearance from black-and-white to brown, cream, or ginger colored, but all have warning coloration while their plumage is predominantly pale or gray depending on the species. The bills, pouches, and bare facial skin of all species become brighter before breeding season commences. <br> <br>
                    Smellicans frequent inland and coastal waters, feeding principally on fish. However, they have been seen to venture further inland to cross from coast to coast. While inland, they feed primarily on small insects, larvae, earthworms, grubs, rodents, lizards, salamanders, frogs, snakes, birds, moles, eggs, berries, roots, leaves, grasses, fungi, and nuts. Rarely, they can be seen acting as scavengers.
                </p>
                <hr>
                <p class="note">A lot of this description is cobbled together from the Wikipedia pages for <a href="https://en.wikipedia.org/wiki/Pelican">pelicans</a>, <a href="https://en.wikipedia.org/wiki/Skunk">skunks</a>, and partially <a href="https://en.wikipedia.org/wiki/Beaver">beavers</a>. The source of the image is hyperlinked in the image.</p>
            </div>
            <div class="bestiary-images bestiary-entry-child">
                <a href="https://kalotyper.tumblr.com/post/659919081890332672/stunfisk-uses-attract-shittycryptids-a-gryphon"><img class="bestiary-image" src="https://64.media.tumblr.com/b25e12fdcf2d292ccb2ececf87aab326/e218ff64ff0f1d09-1c/s500x750/f14ade38b5b32a161af42d87c3ee916a1490396d.jpg" alt="Hand-drawn image of a smellican from Tumblr"></a>
            </div>
        </div>
         */
?>
