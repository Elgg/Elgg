<?php

	if (!empty($vars['requests']) && is_array($vars['requests'])) {
		
		foreach($vars['requests'] as $request)
			if ($request instanceof ElggUser) {
				
?>
	<div class="reportedcontent_content active_report">
		<p class="reportedcontent_detail">
			<?php

				echo $request->name;
			
			?>
		</p>
	</div>
<?php
				
			}
		
	} else {
		
		echo "<div class=\"contentWrapper\">";
		echo "<p>" . elgg_echo('groups:requests:none') . "</p>";
		echo "</div>";
		
	}

?>