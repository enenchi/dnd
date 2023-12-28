<?php

require_once($_SERVER['DOCUMENT_ROOT']."/dnd/resources/pathfinder.php");

// Read json file
$filename = sprintf("%s.json", $_GET["name"]);
if (!file_exists($filename)) {
    echo sprintf("<p><b>%s not found.</b></p>", $_GET["name"]);
    exit(0);
}
$infofile = fopen($filename, "r");
$creature = json_decode(fread($infofile, filesize($filename)));
fclose($infofile);

echo sprintf("<h1>%s</h1>", $creature->name);

echo "<div class=\"bestiary-entry\">";

echo "<div class=\"statblock bestiary-entry-child\">";

/*********************
** overview section **
**********************/
// Name, CR
echo sprintf("<p class=\"title\">%s <span class=\"level\">CR %s</span></p>",
    $creature->name, $creature->cr);
// overview description
if (!file_exists($creature->overviewPath)) {
    echo '<p><b>Missing overview description.</b></p>';
}
else {
    $file = fopen($creature->overviewPath, "r");
    $html = fread($file, filesize($creature->overviewPath));
    fclose($file);
    echo $html;
}
echo "<p>";
echo sprintf('<b>XP %s</b>', pathfinder\get_total_xp_reward($creature->cr));
echo sprintf('<br> %s %s %s %s<br>',
    $creature->alignment, $creature->size,
    pathfinder\link\get_a_tag($creature->type, pathfinder\link\CreatureType::Name, $creature->type),
    $creature->subtype !== null ? ' (' . $creature->subtype . ')' : '');
echo sprintf('<b>Init</b> %+d;', $creature->init);

echo ' <b>Senses</b> ';
$first = true;
foreach($creature->senses as $sense) {
    if (!$first) {
        echo '; ';
    }
    else {
        $first = false;
    }
    $attribute = null;
    switch(strtolower($sense->type)) {
        case "special-ability":
            $attribute = pathfinder\link\SpecialAbility::Name;
            break;
        case "skill":
            $attribute = pathfinder\link\Skill::Name;
            break;
    }
    echo sprintf('<a href="%s">%s</a> %s',
        pathfinder\link\get_link($attribute, $sense->name),
        $sense->name, $sense->value);
}

echo "</p>";
/********************
** defense section **
*********************/
echo "<p class=\"divider\">DEFENSE</p>";
echo "<p>";

// AC
echo sprintf('<b>AC</b> %d, %s %d, %s %d',
    $creature->ac->ac, 
    pathfinder\link\get_a_tag('touch', pathfinder\link\AC::Touch),
    $creature->ac->touch,
    pathfinder\link\get_a_tag('touch', pathfinder\link\AC::FlatFooted),
    $creature->ac->{'flat-footed'});

// AC sources
echo sprintf(' (%+d %s',
    floor(($creature->abilityScores->dex - 10) / 2),
    pathfinder\link\get_a_tag('Dex', pathfinder\link\Ability::DEX));
if ($creature->ac->natural !== 0) {
    echo sprintf(', %+d %s',
    $creature->ac->natural,
    pathfinder\link\get_a_tag('natural', pathfinder\link\AC::Natural));
}
echo ')<br>';

// hp
echo sprintf('<b>hp</b> %d (%dd%d%+d)<br>',
    $creature->health->hp, $creature->health->hd, $creature->health->die,
    floor(($creature->abilityScores->con - 10) / 2) * $creature->health->hd);

// saves
echo sprintf('<b>Fort</b> %+d, <b>Ref</b> %+d, <b>Will</b> %+d <br>',
    $creature->saves->fort, $creature->saves->ref, $creature->saves->will);

// immunities
if (count($creature->immunities) > 0) {
    echo sprintf('<b>Immune</b> ');
    $first = true;
    foreach($creature->immunities as $type) {
        if (!$first) {
            echo ", ";
        }
        else {
            $first = false;
        }
        echo $type;
    }
}

echo '</p>';
/********************
** offense section **
*********************/
echo '<p class="divider">OFFENSE</p>';
echo '<p>';

// Speed
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

// Attacks
echo '<br>';
foreach ($creature->attacks as $attackType => $attackList) {
    echo sprintf('<b>%s</b>', ucwords($attackType));
    echo ' ' . $attackList[0];
    for ($i = 1; $i < count($attackList); $i++) {
        echo ', ' . $attackList[$i];
    }
    echo '<br>';
}

echo '</p>';
/***********************
** statistics section **
************************/
echo '<p class="divider">STATISTICS</p>';
echo '<p>';

// Ability Scores
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

// BAB, CMB, CMD
echo sprintf('<b>Base Atk</b> %+d; <b>CMB</b> %+d; <b>CMD</b> %d%s<br>',
    $creature->bab, $creature->cmb, $creature->cmd->value,
    $creature->cmd->modifier === null ? '' : ' ' . $creature->cmd->modifier);

// Feats
if (count($creature->feats) > 0) {
    echo '<b>Feats</b> ';
    $first = true;
    foreach ($creature->feats as $feat) {
        if (!$first) {
            echo ', ';
        }
        else {
            $first = false;
        }
        $type = pathfinder\link\Feat::Feat;
        switch ($feat->type) {
            case "general":
                $type = pathfinder\link\Feat::General;
                break;
            case "combat":
                $type = pathfinder\link\Feat::Combat;
                break;
            case "monster":
                $type = pathfinder\link\Feat::Monster;
                break;
        }
        echo sprintf('%s%s',
            pathfinder\link\get_a_tag($feat->displayName, $type, $feat->featName),
            ($feat->modifier === null) ? '' : ' ' . $feat->modifier);
    }
}
echo '<br>';

// Skills
if (count($creature->skills) > 0) {
    echo '<b>Skills</b> ';
    $first = true;
    foreach ($creature->skills as $skill) {
        if (!$first) {
            echo ', ';
        }
        else {
            $first = false;
        }
        echo sprintf('%s %+d%s',
            pathfinder\link\get_a_tag($skill->name, pathfinder\link\Skill::Name, $skill->name),
            $skill->value, $skill->modifier !== null ? ' ' . $skill->modifier : '');
    }
}

// Skill Racial Modifiers
if (count($creature->skillRacialModifiers) > 0) {
    echo '; <b>Racial Modifiers</b> ';
    $first = true;
    foreach ($creature->skillRacialModifiers as $skill) {
        if (!$first) {
            echo ', ';
        }
        else {
            $first = false;
        }
        echo sprintf('%+d %s%s',
            $skill->value,
            pathfinder\link\get_a_tag($skill->name, pathfinder\link\Skill::Name, $skill->name),
            $skill->modifier === null ? '' : ' ' . $skill->modifier);
    }
}
echo '<br>';

// Languages
if (count($creature->languages) > 0) {
    echo '<b>Languages</b> ';
    $first = true;
    foreach ($creature->languages as $language) {
        if (!$first) {
            echo ', ';
        }
        else {
            $first = false;
        }
        echo $language;
    }
}
echo '<br>';

// SQ
if (count($creature->sqs) > 0) {
    echo '<b>SQ</b> ';
    $first = true;
    foreach ($creature->sqs as $sq) {
        if (!$first) {
            echo ', ';
        }
        else {
            $first = false;
        }
        echo $sq;
    }
}

echo '</p>';
/******************************
** special abilities section **
*******************************/
echo '<p class="divider">SPECIAL ABILITIES</p>';
echo '<p>';

if (count($creature->specialAbilities) > 0) {
    foreach ($creature->specialAbilities as $ability) {
        echo sprintf("<p class=\"title\">%s</p>", $ability->name);

        if (!file_exists($ability->path)) {
            echo '<p><b>Missing ability description.</b></p>';
        }
        else {
            $file = fopen($ability->path, "r");
            $html = fread($file, filesize($ability->path));
            fclose($file);
            echo $html;
        }
    }
}

echo '</p>';
/********************
** ecology section **
*********************/
echo '<p class="divider">ECOLOGY</p>';
echo '<p>';

echo sprintf('<b>Environment</b> %s<br>', $creature->environment);
echo sprintf('<b>Organization</b> %s<br>', $creature->organization);
echo sprintf('<b>Treasure</b> %s<br>', $creature->treasure);

// description
if (!file_exists($creature->detailsPath)) {
    echo '<p><b>Missing details description.</b></p>';
}
else {
    $file = fopen($creature->detailsPath, "r");
    $html = fread($file, filesize($creature->detailsPath));
    fclose($file);
    echo $html;
}

/*************
**** done ****
**************/
echo '</div></div>';

?>
