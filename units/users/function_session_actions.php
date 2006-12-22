<?php

// Do we have messages?

if (isset($_SESSION['messages']) && sizeof($_SESSION['messages']) > 0) {
    if (isset($messages) && sizeof($messages) > 0) {
        array_merge($messages, $_SESSION['messages']);
    } else {
        $messages = $_SESSION['messages'];
    }
    unset($_SESSION['messages']);
}

// Has 'action' been set?
$action = optional_param('action');
switch ($action) {
    case "log_on":        
        run("users:log_on");  //TODO remove this - deprecated
        break;
    case "log_off":        
        run("users:log_off"); //TODO remove this - deprecated
        break;
    case "register":    
        run("users:register");
        break;
}

        
?>