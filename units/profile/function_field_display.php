<?php

    global $profile_id;
    
    $field = $parameter[0];
    $allvalues = $parameter[1];
    
    if (sizeof($field) >= 2) {
    
        $value = get_record('profile_data','name',$field[1],'owner',$profile_id);
        
        $value->value = "";
        $value->access = "";
        foreach($allvalues as $curvalue) {
            if ($curvalue->name == stripslashes($field[1])) {
                $value = $curvalue;
            }
        }
        
        /* if (isset($value[0])) {
            $value = $value[0];
        } else {
            $value->value = "";
            $value->access = "";
        } */

        if ((($value->value != "" && $value->value != "blank")) && run("users:access_level_check", $value->access)) {
            $name = $field[0];
            $column1 = display_output_field(array($value->value,$field[2],$field[1],$field[0],$value->ident));
            $run_result .= templates_draw(array(
                                    'context' => 'databox1',
                                    'name' => $name,
                                    'column1' => $column1
                                )
                                );
        }
    }

?>