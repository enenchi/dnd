<?php
namespace pathfinder\link;

enum Skill {
    case Name; // Use name field
}

enum Ability {
    case STR;
    case DEX;
    case CON;
    case INT;
    case WIS;
    case CHA;
    case AbilityScore;
}

enum AC {
    case Touch;
    case FlatFooted;
    case Natural;
    case ArmorClass;
}

enum Feat {
    case General; // Use name field
    case Combat; // Use name field
    case Monster; // Use name field
    case Feat;
    // TODO
}

enum CreatureType {
    case Name; // Use name field
}

enum SpecialAbility {
    case Name; // Use name field
}

/**
 * Formats/cleans a string to be accepted by the pathfinder TOC link
 */
function clean_string(string $in, bool $upperFirst, string $spaceFiller) {
    $in = str_replace(')', '', str_replace('(', '', $in));
    if (str_contains($in, ' ')) {
        return strtolower(str_replace(' ', $spaceFiller, $in));
    }
    if ($upperFirst) {
        return ucfirst(strtolower($in));
    }
    return strtolower($in);
}

/**
 * Gets a pathfinder link based on attribute and an optional name.
 * \note Knowledge skills will correctly parse out the specific knowledge type.
 */
function get_link($attribute, $name = "") {
    if ($attribute instanceof Skill) {
        $name = clean_string($name, false, '-');
        if (str_contains($name, "knowledge")) {
            return 'https://www.d20pfsrd.com/skills/knowledge/';
        }
        return sprintf("https://www.d20pfsrd.com/skills/%s", $name);
    }
    if ($attribute instanceof Ability) {
        switch ($attribute) {
            case Ability::STR:
                return 'https://www.d20pfsrd.com/basics-ability-scores/ability-scores#TOC-Strength-Str-';
            case Ability::DEX:
                return 'https://www.d20pfsrd.com/basics-ability-scores/ability-scores#TOC-Dexterity-Dex-';
            case Ability::CON:
                return 'https://www.d20pfsrd.com/basics-ability-scores/ability-scores#TOC-Constitution-Con-';
            case Ability::INT:
                return 'https://www.d20pfsrd.com/basics-ability-scores/ability-scores#TOC-Intelligence-Int-';
            case Ability::WIS:
                return 'https://www.d20pfsrd.com/basics-ability-scores/ability-scores#TOC-Wisdom-Wis-';
            case Ability::CHA:
                return 'https://www.d20pfsrd.com/basics-ability-scores/ability-scores#TOC-Charisma-Cha-';
            default:
                return 'https://www.d20pfsrd.com/basics-ability-scores/ability-scores/';
        }
    }
    if ($attribute instanceof AC) {
        switch ($attribute) {
            case AC::Touch:
                return 'https://www.d20pfsrd.com/gamemastering/combat#TOC-Touch-Attacks';
            case AC::FlatFooted:
                return 'https://www.d20pfsrd.com/gamemastering/conditions#TOC-Flat-Footed';
            case AC::Natural:
                return 'https://www.d20pfsrd.com/basics-ability-scores/glossary#TOC-Natural-Armor-Bonus';
            default:
                return 'https://www.d20pfsrd.com/gamemastering/Combat/#TOC-Armor-Class';
        }
    }
    if ($attribute instanceof Feat) {
        switch ($attribute) {
            case Feat::General:
                return sprintf('https://www.d20pfsrd.com/feats/general-feats/%s/', clean_string($name, false, '-'));
            case Feat::Combat:
                return sprintf('https://www.d20pfsrd.com/feats/combat-feats/%s/', clean_string($name, false, '-'));
            case Feat::Monster:
                return sprintf('https://www.d20pfsrd.com/feats/monster-feats/%s/', clean_string($name, false, '-'));
            default:
                return 'https://www.d20pfsrd.com/feats/';
        }

        // TODO
    }
    if ($attribute instanceof CreatureType) {
        return sprintf("https://www.d20pfsrd.com/bestiary/rules-for-monsters/creature-types/#%s", clean_string($name, false, '_'));
    }
    if ($attribute instanceof SpecialAbility) {
        return sprintf("https://www.d20pfsrd.com/gamemastering/special-abilities/#%s", clean_string($name, false, '_'));
    }
    return 'https://www.d20pfsrd.com';
}

/**
 * Gets a pathfinder link based on attribute and an optional name, then wraps
 * an <a> tag around it with the given innerHTML
 * \note Knowledge skills will correctly parse out the specific knowledge type and not link it.
 */
function get_a_tag($innerHTML, $attribute, $name = "") {
    $link = get_link($attribute, $name);
    if ($attribute instanceof Skill && str_contains($link, "knowledge")) {
        $begin = strpos($name, '(') + 1;
        $end = strrpos($name, ')');
        return sprintf('<a href="%s">Knowledge</a> (%s)',
            $link, ucwords(substr($name, $begin, $end - $begin)));
    }
    return sprintf('<a href="%s">%s</a>', $link, $innerHTML);
}

?>
