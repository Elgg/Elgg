<?php

    $options = array('Male' => "Male", 'Female' => "Female");
    foreach($options as $option => $label) {
        if ($option != $vars['value']) {
            $selected = "";
        } else {
            $selected = "checked = \"checked\"";
        }
        echo "<label><input type=\"radio\" {$vars['js']} name=\"{$vars['internalname']}\" value=\"".htmlentities($option)."\" {$selected} />{$label}</label><br />";
    }

?> 