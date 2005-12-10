<?php

		include("../includes.php");
	
		$people = db_query("select * from users where ident != 8");
		$friends = db_query("select * from friends where owner != 8 and friend != 8");
		
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