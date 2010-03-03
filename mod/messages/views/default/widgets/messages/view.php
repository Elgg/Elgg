
	<?php
	
	    $num_messages = count_unread_messages();
	    
        if($num_messages  == 0)
		    echo "<h3 class=\"new_messages_count\">You have no new messages.</h3>";
		else {
		    echo "<h3 class=\"new_messages_count\">" . $num_messages . " new message(s).</h3>";
		    echo "<a href=\"" . $vars['url'] . "pg/messages/" . $_SESSION['user']->username ."\">check them out</a>";
	    }

	?>
