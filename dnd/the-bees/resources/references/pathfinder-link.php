<?php

header('Content-Type: text/plain; charset-utf-8');

$name = $_GET["name"];

switch (strtolower($_GET["type"])) {
    case "skill":
        echo sprintf("https://www.d20pfsrd.com/skills/%s", strtolower($name));
        break;
    case "ability":
        switch (strtolower($name)) {
            case "str":
                echo 'https://www.d20pfsrd.com/basics-ability-scores/ability-scores#TOC-Strength-Str-';
                break;
            case "dex":
                echo 'https://www.d20pfsrd.com/basics-ability-scores/ability-scores#TOC-Dexterity-Dex-';
                break;
            case 'con':
                echo 'https://www.d20pfsrd.com/basics-ability-scores/ability-scores#TOC-Constitution-Con-';
                break;
            case 'int':
                echo 'https://www.d20pfsrd.com/basics-ability-scores/ability-scores#TOC-Intelligence-Int-';
                break;
            case 'wis':
                echo 'https://www.d20pfsrd.com/basics-ability-scores/ability-scores#TOC-Wisdom-Wis-';
                break;
            case 'cha':
                echo 'https://www.d20pfsrd.com/basics-ability-scores/ability-scores#TOC-Charisma-Cha-';
                break;
            default:
                echo 'https://www.d20pfsrd.com/basics-ability-scores/ability-scores/';
                break;
        }
        break;
    case "ac":
        switch (strtolower($name)) {
            case "touch":
                echo 'https://www.d20pfsrd.com/gamemastering/combat#TOC-Touch-Attacks';
                break;
            case 'flat-footed':
                echo 'https://www.d20pfsrd.com/gamemastering/conditions#TOC-Flat-Footed';
                break;
            case 'natural':
                echo 'https://www.d20pfsrd.com/basics-ability-scores/glossary#TOC-Natural-Armor-Bonus';
                break;
            default:
                echo 'https://www.d20pfsrd.com/gamemastering/Combat/#TOC-Armor-Class';
                break;
        }
        break;
    case "feat":
    default:
        echo 'https://www.d20pfsrd.com';
        break;
}

?>
