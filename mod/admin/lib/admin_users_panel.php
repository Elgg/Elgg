<?php

    // Users panel
    
    // $parameter = a row from the elgg.users database
    
    if (isset($parameter)) {
        $profile_link = ' <a href="' . get_url($parameter->ident, 'admin::userdetails') . '"><small>| ' . __gettext('User details') . '</small></a>';

        $run_result .= templates_draw(array(
                        'context' => 'adminTable',
                        'name' => "<a href=\"" . url . "profile/index.php?profile_id=" .$parameter->ident . "\" >" . stripslashes($parameter->name) . "</a>",
                        'column1' => "<p><strong>" . $parameter->username . '</strong>'. $profile_link . "</p>",
                        'column2' => "<a href=\"mailto:" . $parameter->email. "\" >" . $parameter->email . "</a>"
                    )
                    );
        
    }

?>