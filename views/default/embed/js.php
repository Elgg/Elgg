
	function elggUpdateContent(content, entityname) {
		content = ' ' + content + ' ';
		<?php
			echo elgg_view('embed/addcontentjs');
		?>
	}