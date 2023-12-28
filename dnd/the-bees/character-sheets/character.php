<!DOCTYPE HTML>
<html>
<head>
    <meta charset="utf-8">
    <link rel="stylesheet" href="/dnd/css/book.css">
    <style>
        body, html {
            height: 100%;
        }
        
        .tablink {
            background-color: rgba(0, 0, 0, 0);
            color: black;
            float: left;
            border: none;
            outline: none;
            cursor: pointer;
            padding: 16px 16px;
            width: auto;
        }
        
        .tablink:hover {
            background-color: rgba(0, 0, 0, 0.25);
        }

        .tabcontent {
            color: black;
            display: none;
            padding: 100px 16px;
            height: 100%;
        }

        input[type="number"] {
            background-color: rgba(0, 0, 0, 0.05);
            text-align: center;
            border: none;
            outline: none;
        }

        .dropdown-content {
            display: none;
            position: absolute;
            box-shadow: 0px 8px 16px 0px rgba(0, 0, 0, 0.25);
            z-index: 1;
        }
        
    </style>
<script>
var currentTab = null;

function openTab(event, tabId) {
    if (currentTab === tabId) {
        return;
    }
    if (currentTab !== null) {
        currentTab.style.display = "none";
    }
    currentTab = document.getElementById(tabId);
    currentTab.style.display = "block";
}
</script>

</head>
<body>
    <p><a href="/dnd/the-bees">The Bees</a> > <a href="/dnd/the-bees/character-sheets">Character Sheets</a> > </p>
    <h1>Name</h1>

    <div class="tab">
        <button class="tablink" id="defaultTablink" onclick="openTab(event, 'Core')">Core</button>
        <button class="tablink" onclick="openTab(event, 'Combat')">Combat</button>
    </div>

    <div id="Core" class="tabcontent">
        <table id="abilityTable">
            <thead>
                <tr>
                    <th colspan=6>ABILITIES</th>
                </tr>
                <tr>
                    <th></th>
                    <th>Ability Score</th>
                    <th>Item Bonus</th>
                    <th>Ability Modifier</th>
                    <th>Temporary Score</th>
                    <th>Temporary Modifier</th>
                </tr>
            </thead>
        </table>
        
        <table id="skillTable">
            <thead>
                <tr>
                    <th colspan=9>SKILLS</th>
                </tr>
                <tr>
                    <th></th>
                    <th>Untrained</th>
                    <th>Skill Bonus</th>
                    <th>Mod</th>
                    <th>Class Skills +3</th>
                    <th>Ranks</th>
                    <th>Racial, Feats</th>
                    <th>Misc</th>
                    <th>Armor Check Penalty</th>
                </tr>
            </thead>
        </table>
    </div>

    <div id="Combat" class="tabcontent">

    </div>

<script>
document.getElementById("defaultTablink").click();

class Skill {
    static SKILLS = new Map([
        ["Acrobatics",      "dex"],
        ["Appraise",        "int"],
        ["Bluff",           "cha"],
        ["Climb",           "str"],
        ["Diplomacy",       "cha"],
        ["Disable Device",  "dex"],
        ["Disguise",        "cha"],
        ["Escape Artist",   "dex"],
        ["Fly",             "dex"],
        ["Handle Animal",   "cha"],
        ["Heal",            "wis"],
        ["Intimidate",      "cha"],
        ["Knowledge (Arcana)",          "int"],
        ["Knowledge (Dungeoneering)",   "int"],
        ["Knowledge (Engineering)",     "int"],
        ["Knowledge (Geography)",       "int"],
        ["Knowledge (History)",         "int"],
        ["Knowledge (Local)",           "int"],
        ["Knowledge (Nature)",          "int"],
        ["Knowledge (Nobility)",        "int"],
        ["Knowledge (Planes)",          "int"],
        ["Knowledge (Religion)",        "int"],
        ["Linguistics",         "int"],
        ["Perception",          "wis"],
        ["Ride",                "dex"],
        ["Sense Motive",        "wis"],
        ["Sleight of Hand",     "dex"],
        ["Spellcraft",          "int"],
        ["Stealth",             "dex"],
        ["Survival",            "wis"],
        ["Swim",                "str"],
        ["Use Magic Device",    "cha"]
    ]);
    static UNTRAINED = new Set([
        "Acrobatics", "Appraise", "Bluff", "Climb", "Craft", "Diplomacy",
        "Disguise", "Escape Artist", "Fly", "Heal", "Intimidate", "Perception",
        "Perform", "Ride", "Sense Motive", "Stealth", "Survival", "Swim"
    ]);
    static ARMOR_CHECK = new Set([
        "Acrobatics", "Climb", "Disable Device", "Escape Artist", "Fly", "Ride",
        "Sleight of Hand", "Stealth", "Swim"
    ]);

    static sumMap(map) {
        let sum = 0;
        for (const value of map.values()) {
            sum += value;
        }
        return sum;
    }

    constructor(name, ability, isClassSkill, isUntrained, hasArmorCheck, ranks, racialFeats, misc) {
        this.name = name;
        this.ability = ability;
        
        this.el = document.createElement("tr");
        // name
        this.nameEl = this.el.insertCell(-1);
        this.nameEl.innerHTML = name;
        // untrained
        this.isUntrainedEl = document.createElement("input");
        this.isUntrainedEl.setAttribute("type", "checkbox");
        this.isUntrainedEl.checked = isUntrained;
        this.isUntrainedEl.addEventListener("input", () => {
            this.isUntrained = this.isUntrainedEl.checked;
        });
        this.el.insertCell(-1).appendChild(this.isUntrainedEl);
        // skill bonus
        this.skillBonusEl = this.el.insertCell(-1);
        // ability mod
        this.modEl = this.el.insertCell(-1);
        // class skill
        this.isClassSkillEl = document.createElement("input");
        this.isClassSkillEl.setAttribute("type", "checkbox");
        this.isClassSkillEl.checked = isClassSkill;
        this.isClassSkillEl.addEventListener("input", () => {
            this.isClassSkill = this.isClassSkillEl.checked;
        });
        this.el.insertCell(-1).appendChild(this.isClassSkillEl);
        // ranks
        this.ranksEl = document.createElement("input");
        this.ranksEl.setAttribute("type", "number");
        this.ranksEl.value = ranks;
        this.ranksEl.addEventListener("input", () => {
            this.ranks = this.ranksEl.valueAsNumber;
        });
        this.el.insertCell(-1).appendChild(this.ranksEl);
        // racial, feats
        this.racialFeatsEl = this.el.insertCell(-1);
        this.racialFeatsEl.innerHTML = Skill.sumMap(racialFeats);
        this.racialFeatsDropdown = document.createElement("div");
        this.racialFeatsDropdown.setAttribute("class", "dropdown-content");
        this.racialFeatsEl.addEventListener("click", () => {
            console.log("rf menu");
        });
        // misc
        this.miscEl = this.el.insertCell(-1);
        this.miscEl.innerHTML = Skill.sumMap(misc);
        this.miscDropdown = document.createElement("div");
        this.miscDropdown.setAttribute("class", "dropdown-content");
        this.miscEl.addEventListener("click", () => {
            console.log("misc menu");
        });
        // armor check penalty
        if (hasArmorCheck) {
            this.armorCheckEl = document.createElement("input");
            this.armorCheckEl.setAttribute("type", "number");
            // TODO: link with armor and weight and etc
            this.el.insertCell(-1).appendChild(this.armorCheckEl);
        }
        else {
            this.el.insertCell(-1);
        }
        // set values
        this._isUntrained = isUntrained;
        this._isClassSkill = isClassSkill;
        this._ranks = ranks;
        this._racialFeats = racialFeats;
        this._misc = misc;
        this._hasArmorCheck = hasArmorCheck;
        this.updateSkillBonus();
    }

    set isUntrained(b) {
    this._isUntrained = b;
        this.updateSkillBonus();
        sync();
    }
    set isClassSkill(b) {
        this._isClassSkill = b;
        this.updateSkillBonus();
        sync();
    }
    set ranks(n) {
        this._ranks = n;
        this.updateSkillBonus();
        sync();
    }
    set racialFeats(n) {
        this.racialFeats = n;
        this.updateSkillBonus();
        sync();
    }
    set misc(n) {
        this.misc = n;
        this.updateSkillBonus();
        sync();
    }

    get skillBonus() {
        return this.updateSkillBonus();
    }

    updateSkillBonus() {
        if (!this._isUntrained && this._ranks < 1) {
            this.skillBonusEl.innerHTML = "-";
            return null;
        }
        let sum = this._ranks;
        if (this._isClassSkill && this._ranks >= 1) {
            sum += 3;
        }
        sum += Skill.sumMap(this._racialFeats) + Skill.sumMap(this._misc);
        // TODO: armor check
        this.skillBonusEl.innerHTML = sum;
        return sum;
    }
}

class Ability {
    static ABILITIES = ["str", "dex", "con", "int", "wis", "cha"];

    constructor(name, score, item, temp) {
        this.name = name;
        this._score = score;
        this._item = item;
        this._temp = temp;

        this.el = document.createElement("tr");
        // name
        this.el.insertCell(-1).innerHTML = this.name.toUpperCase();
        // scor=
        this.scoreEl = document.createElement("input");
        this.scoreEl.setAttribute("type", "number");
        this.scoreEl.value = score;
        this.scoreEl.addEventListener("input", () => {
            this.score = this.scoreEl.valueAsNumber;
        });
        this.el.insertCell(-1).appendChild(this.scoreEl);
        // item
        this.itemEl = document.createElement("input");
        this.itemEl.setAttribute("type", "number");
        this.itemEl.value = item;
        this.itemEl.addEventListener("input", () => {
            this.item = this.itemEl.valueAsNumber;
        });
        this.el.insertCell(-1).appendChild(this.itemEl);
        // ability mod
        this.modEl = this.el.insertCell(-1);
        this.modEl.innerHTML = this.mod;
        // temp score
        this.tempScoreEl = document.createElement("input");
        this.tempScoreEl.setAttribute("type", "number");
        this.tempScoreEl.value = temp;
        this.tempScoreEl.addEventListener("input", () => {
            this.temp = this.tempScoreEl.valueAsNumber;
        });
        this.el.insertCell(-1).appendChild(this.tempScoreEl);
        // temp mod
        this.tempModEl = this.el.insertCell(-1);
        this.modEl.innerHTML = this.tempMod;

        this.updateMod();
        this.updateTemp();
    }

    get score() { return this._score; }
    set score(v) {
        this._score = v;
        this.updateMod();
        sync();
    }

    get item() { return this._item; }
    set item(v) {
        this._item = v;
        this.updateMod();
        sync();
    }

    get temp() { return this._temp; }
    set temp(v) {
        this._temp = v;
        this.updateTemp();
        sync();
    }

    updateMod() {
        this.modEl.innerHTML = this.mod;
        return this.mod;
    }

    updateTemp() {
        this.tempModEl.innerHTML = this.tempMod;
        return this.tempMod;
    }
    
    get mod() {
        return Math.floor((this.score + this.item - 10) / 2);
    }

    get tempMod() {
        return Math.floor((this.temp - 10) / 2);
    }
}


class Character {
    
    constructor() {
        this.abilities = new Map();
        for (let i = 0; i < Ability.ABILITIES.length; i++) {
            this.abilities.set(Ability.ABILITIES[i], new Ability(
                Ability.ABILITIES[i], 10, 0, 10));
        }
        this.skills = new Map();
        for (const [skill, mod] of Skill.SKILLS) {
            this.skills.set(skill, new Skill(skill, this.abilities.get(mod),
                false, Skill.UNTRAINED.has(skill), Skill.ARMOR_CHECK.has(skill),
                0, new Map(), new Map()));
        }
    }

}

var character = null;

function sync() {

}

window.onload = function() {
    character = new Character();
    // add all abilities
    let abilityBody = document.createElement("tbody");
    document.getElementById("abilityTable").appendChild(abilityBody);
    for (const ability of character.abilities.values()) {
        abilityBody.appendChild(ability.el);
    }
    // add all skills
    let skillBody = document.createElement("tbody");
    document.getElementById("skillTable").appendChild(skillBody);
    for (const skill of character.skills.values()) {
        skillBody.appendChild(skill.el);
    }
}

</script>
</body>
</html>
