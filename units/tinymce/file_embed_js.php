<?php

    if (logged_on) {
        
        $run_result = "\ntinyMCE.execCommand(\"mceInsertContent\",true,\"{{file:\" + form.weblog_add_file.options[form.weblog_add_file.selectedIndex].value + \"}}\");\n";
        
    }

?>