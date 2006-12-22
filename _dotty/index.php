<?php

require_once(dirname(dirname(__FILE__))."/includes.php");
    
$people = get_records_select('users','ident != ?',array(8));
$friends = get_records_select('friends','owner != ? AND friend != ?',array(8,8));

echo "digraph G {\n\n";

foreach($people as $person) {
    
    $name = stripslashes($person->name);
    $name = preg_replace('/[^\w ]/i','',$name);
    
    echo "\tuser" . $person->ident;
    /*echo " [";
            if ($person->user_type == "community") {
                echo "fillcolor=\"gold\"";
            }
            echo "]";*/
    echo ";\n";
    
}

foreach($friends as $friend) {
    
    echo "\tuser" . $friend->owner . " -> user" . $friend->friend . ";\n";
    
}

echo "\n}";

?>