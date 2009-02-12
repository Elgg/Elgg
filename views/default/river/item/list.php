
<div class="river_item_list">
<?php

	if (isset($vars['items']) && is_array($vars['items'])) {
		
		if (!empty($vars['items']))
		foreach($vars['items'] as $item) {
			
			echo elgg_view_river_item($item);
			
		}
		
	}

?>
</div>