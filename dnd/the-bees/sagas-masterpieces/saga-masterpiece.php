<!DOCTYPE HTML>
<html>
    <head>
        <meta charset="utf-8">
        <link rel="stylesheet" href="/dnd/css/book.css">
    </head>
    <body>
        <p><a href="/dnd/the-bees">The Bees</a> > <a href="/dnd/the-bees/items">Items</a> ></p>
        <div class="item-block">
<?php
$itemfilename = sprintf("%s.json", $_GET["name"]);
$descfilename = sprintf("%s.html", $_GET["name"]);
if (!file_exists($itemfilename) or !file_exists($descfilename)) {
    echo "<p><b>Item not found</b></p>";
}
else {
    $itemfile = fopen($itemfilename, "r");
    $item = json_decode(fread($itemfile, filesize($itemfilename)));
    fclose($itemfile);

    $descfile = fopen($descfilename, "r");
    $desc = fread($descfile, filesize($descfilename));
    fclose($descfile);

    echo "<h1>" . $item->name . "</h1>";
    
    echo "<p><b>Aura</b> " . $item->aura .  "; <b>CL</b> " . $item->cl .
        "; <b>Slot</b> " . $item->slot . "; <b>Price</b> " . $item->price .
        "; <b>Weight</b> " . $item->weight . "</p>";

    echo '<p class="divider">DESCRIPTION</p>';
    
    echo $desc;

    echo '<p class="divider">CONSTRUCTION REQUIREMENTS</p>';
    /*
    // build feats list
    if (count($item->feats) >= 2) {
        $feats = "";
        $feats .= '<a href="' . $item->feats[1] . '">' .
            $item->feats[0] . '</a>';
        for ($i = 2; $i < count($item->feats); $i+=2) {
            $feats .= ', <a href="' . $item->feats[$i + 1] . '">' .
                $item->feats[$i] . '</a>';
        }
        echo "<p><b>Feats</b> " . $feats . ";";
    }
    // build spells list
    if (count($item->spells) >= 2) {
        $spells = "";
        $spells .= '<a href="' . $item->spells[1] . '">' .
            $items->spells[0] . '</a>';
        for ($i = 0; $i < count($item->spells); $i += 2) {
            $spells .= ', <a href="' .$item.spells[$i + 1] . '">' .
                $item->spells[$i] . '</a>';
        }
        echo " <p><b>Spells</b> " . $spells . ";";
    }
    // build special list
    if (count($item->special_reqs) > 0) {
        $special = "";
        foreach ($item->special_reqs as $req) {
            
        }
        echo " <p><b>Special</b> " . $
    }
     */
    echo "<p>";
    if ($item->feats != null) {
        echo "<b>Feats</b> " . $item->feats . "; ";
    }
    if ($item->spells != null) {
        echo "<b>Spells</b> " . $item->spells . "; ";
    }
    if ($item->special_req != null) {
        echo "<b>Special</b> " . $item->special_req . "; ";
    }
    echo "<b>Cost</b> " . $item->cost . ";</p>";
}

?>
        </div>
    </body>
</html>
