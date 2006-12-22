<?php

// If isset $parameter (string), returns true if spam

if (isset($parameter)) {
    if (!$spam = get_field('datalists','value','name','antispam')) {
        $spam = "";
    }
    
    $spam = str_replace("\r","",$spam);
    $spam = explode("\n",$spam);
    
    foreach($spam as $regexp) {
        if (strlen($regexp) > 0) {
            if (substr($regexp,0,1) != "#") {
                if (@preg_match("/" . trim($regexp) . "/is", $parameter)) {
                    $run_result = true;
                }
            }
        }
    }
    
}

?>