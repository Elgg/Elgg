<?php

    // Replace {{file:$id}} with links to files
    
        if (isset($parameter)) {
            $functionbody = <<< END
            
            return run("files:links:make",\$matches[1]);
            
END;
            $run_result = preg_replace_callback("/\{\{file:([0-9]+)\}\}/i",create_function('$matches',$functionbody),$run_result);
        }

?>