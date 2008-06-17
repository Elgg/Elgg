<?php

    global $page_owner;

    if (sizeof($parameter) >= 2) {
    
        if (!isset($data['profile:preload'][$parameter[1]])) {

            if (!$value = get_record('profile_data','name',$parameter[1],'owner',$page_owner)) {
                $value = new StdClass;
                $value->value = null;
                $value->ident = null;
                $value->access = default_access;
            }
        
        } else {
            
            unset($value);
            $value->value = $data['profile:preload'][$parameter[1]];
            $value->access = default_access;
            
        }
        
        $name = <<< END
                    <label for="{$parameter[1]}">
                        <b>{$parameter[0]}</b>
END;
        if (isset($parameter[3])) {
            $name .= "<br /><i>" . $parameter[3] . "</i>";
        }
        $name .= <<< END
                    </label>
END;
    
        if (sizeof($parameter) < 3) {
            $parameter[2] = "text";
        }
        $column1 = display_input_field(array("profiledetails[" . $parameter[1] . "]",$value->value,$parameter[2],$parameter[1],$value->ident,$page_owner));

        $column2 = "<label>". __gettext("Access Restriction:") ."<br />";
        $column2 .= run("display:access_level_select",array("profileaccess[".$parameter[1] . "]",$value->access)) . "</label>";
        
        $run_result .= templates_draw(array(
                            'context' => 'databox',
                            'name' => $name,
                            'column1' => $column1,
                            'column2' => $column2
                        )
                        );
        
    }

?>