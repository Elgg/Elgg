<?php

	$body = "<form action=\"".url . $_SESSION['username']."/\" method=\"post\">";

	// Cycle through all defined profile detail fields and display them

	if (isset($data['profile:details']) && sizeof($data['profile:details']) > 0) {
	
		foreach($data['profile:details'] as $field) {
			$body .= run("profile:editfield:display",$field);
		}

	}

	$body .= <<< END

	<p align="center">
		<label>
			Submit details:
			<input type="submit" name="submit" value="Go" />
		</label>
		<input type="hidden" name="action" value="profile:edit" />
	</p>

</form>
END;

	$run_result .= $body;
	
?>