<?php

	$descOne = gettext("This is a help file for the Groups functions.");
	$descTwo = gettext("When you create an access group, add people to it then select it as your access restriction on a weblog post only the people in that group will be able to see that weblog post and so on for all elements in your learning landscape.");
	$run_result .= <<< END

	<p>$descOne</p>
	<p>$descTwo</p>
END;

?>