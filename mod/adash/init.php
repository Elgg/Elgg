<?php

    global $CFG, $template;
    $template['css'] .= file_get_contents($CFG->dirroot . "mod/adash/css");

?>