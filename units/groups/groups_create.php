<?php

	$run_result .= <<< END

<h2>
	Create a new group
</h2>
<form action="" method="post">
	<label>
		Group name:
		<input type="text" name="name" value="" />
	</label>
	<input type="submit" value="Create" />
	<input type="hidden" name="action" value="group:create" />
</form>

END;

?>