<?php

require_once(dirname(dirname(__FILE__))."/includes.php");

header("Content-Type: text/plain");

$people = get_records_select('users','ident != ?',array(8), $sort='', $fields='ident,username,name,user_type');
$friends = get_records_select('friends','owner != ? AND friend != ?',array(8,8), $sort='', $fields='owner,friend');

echo "digraph G {\n\n";

foreach($people as $person) {

    $name = stripslashes($person->name);
    $name = preg_replace('/[^\w ]/i','',$name);

    echo "\tu" . $person->ident;
    echo " [";
    echo 'label="' . $name . '" ';
    if ($person->user_type == "community") {
        echo "fillcolor=\"gold\"";
    }
    echo "]";
    echo ";\n";

}

foreach($friends as $friend) {

    echo "\tu" . $friend->owner . " -> u" . $friend->friend . ";\n";

}

echo "\n}";

?>