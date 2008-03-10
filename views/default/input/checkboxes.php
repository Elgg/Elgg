<?php

    foreach($vars['options'] as $option) {
        if (!in_array($option,$vars['value'])) {
            $selected = "";
        } else {
            $selected = "checked = \"checked\"";
        }
        echo "<label><input type=\"checkbox\" {$vars['js']} name=\"{$vars['internalname']}[]\" {$selected} value=\"".htmlentities($option)."\" {$selected} />{$option}</label><br />";
    }

?> 