<?php

	$username = $_SESSION['username'];
	$url = url;
	$portfolio = url . $username;

	$run_result .= <<< END
	
		<p>
			<b>
				<a href="$portfolio">View your profile!</a>
				Your portfolio is the main way people find out about you. You can
				<a href="{$url}profile/edit.php">edit your details</a> and choose
				exactly what you want to share with whom.
			</b>
		</p>
	
END;

?>