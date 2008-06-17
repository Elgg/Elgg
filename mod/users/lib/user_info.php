<?php

global $profile_id;
global $CFG;

// Given a title and series of user IDs as a parameter, will display a box containing the icons and names of each specified user
// $parameter[0] is the title of the box; $parameter[1..n] is the user ID

if (isset($parameter[0]) && sizeof($parameter) > 1 /*&& $parameter[1][0] != 0*/) {
    
    if (sizeof($parameter[1]) > 1) {
        $span = 2;
    } else {
        $span = 1;
    }
    
    $name = $parameter[0];
    
    $i = 1;
    if (sizeof($parameter[1]) == 0) {
        
        $body = "<p>" . __gettext("None.") . "</p>";
        
            if (isset($parameter[2]) && $parameter[2] != "") {
                $body .= "<p>" . $parameter[2] . "</p>";
            }
            
    } else {
        $body = <<< END
            
    <ul>
            
END;
        foreach($parameter[1] as $key => $ident) {
            $ident = (int) $ident;
            $info = get_record('users','ident',$ident);
            $_SESSION['user_info_cache'][$ident] = $info;
            $info = $info;
            // }
            $info = $_SESSION['user_info_cache'][$ident];

            $w = 100;
            if (sizeof($parameter[1]) > 1) {
                $w = 50;
            }
            
            $username = user_name($info->ident);
            $usermenu = '';

            $body .= <<< END
        <li>
            <a href="{$CFG->wwwroot}{$info->username}/">{$username}</a>
        </li>
END;
            
            if ($span == 1 || ($span == 2 && ($i % 2 == 0))) {
                $body .= "";
            }
            $i++;
        }
        $body .= "";
            
            if (isset($parameter[2]) && $parameter[2] != "") {
                $body .= "<li><p>" . $parameter[2] . "</p></li>";
            }
            
            $body .= "</ul>";
    }
    
    $run_result .= templates_draw(array(
                                        'context' => 'sidebarholder',
                                        'title' => $name,
                                        'body' => $body
                                        )
                                  );
    
}

?>