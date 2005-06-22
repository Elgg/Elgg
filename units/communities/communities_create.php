<?php

	if (isset($_SESSION['comm_name'])) {
		$comm_name = $_SESSION['comm_name'];
	}
	if (isset($_SESSION['comm_username'])) {
		$comm_name = $_SESSION['comm_username'];
	}

	$run_result .= <<< END

<div class="community_create">
	<p>
		&nbsp;
	</p>
	<h2>
		Create a new community
	</h2>
	<form action="" method="post">
		<p>
			<label>
				Community name:
				<input type="text" name="comm_name" value="$comm_name" />
			</label>
		</p>
		<p>
			<label>
				Username for community:
				<input type="text" name="comm_username" value="$comm_username" />
			</label>
		</p>
		<p>
			<input type="submit" value="Create" />
			<input type="hidden" name="action" value="community:create" />
		</p>
	</form>
</div>

END;

?>