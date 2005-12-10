<?php

	$username = $_SESSION['username'];
	$url = url;
	$portfolio = url . $username;
       $viewProfile = gettext("View your profile!"); // gettext variable
       $viewProfile2 = gettext("Your portfolio is the main way people find out about you. You can"); // gettext variable
       $viewProfile3 = gettext("edit your details"); // gettext variable
       $viewProfile4 = gettext("and choose exactly what you want to share with whom."); // gettext variable

	$run_result .= <<< END
	
		<p>
			<b>
				<a href="$portfolio">$viewProfile</a>
				$viewProfile2
				<a href="{$url}profile/edit.php">$viewProfile3</a> $viewProfile4
			</b>
		</p>
	
END;

?>