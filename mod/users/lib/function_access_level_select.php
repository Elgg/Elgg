<?php

    if ($parameter[1] == "PRIVATE") {
        $parameter[1] = "user" . $_SESSION['userid'];
    }

    $run_result .= "<select name=\"". $parameter[0] . "\">";

    if (!empty($data['access'])) {
        foreach($data['access'] as $access) {
            if ($parameter[1] == $access[1] && $parameter[1] != "") {
                $selected = ' selected="selected" ';
            } else {
                $selected = "";
            }
            $run_result .= <<< END
    <option value="{$access[1]}" {$selected}>
        {$access[0]}
    </option>
END;
        }
    }

    $run_result .= "</select>";
    
?>