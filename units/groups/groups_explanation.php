<?php

    global $CFG;

    $descOne = __gettext("Access controls let you control exactly who sees everything you upload to {$CFG->sitename}, including files, blog posts and profile items.");
    $descTwo = __gettext("This screen lets you create new access controls. To get started, add a new access control by typing in its name below.");
    $run_result .= <<< END

    <p>$descOne</p>
    <p>$descTwo</p>
END;

?>