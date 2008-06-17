<?php

    global $CFG;

    $descOne = sprintf(__gettext("Access controls let you control exactly who sees everything you upload to %s, including files, blog posts and profile items."),$CFG->sitename); 
    $descTwo = __gettext("This screen lets you create new access controls. To get started, add a new access control by typing in its name below.");
    $run_result .= <<< END

    <p>$descOne</p>
    <p>$descTwo</p>
END;

?>