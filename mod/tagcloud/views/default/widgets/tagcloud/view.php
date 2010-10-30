<?php

	$num_items = $vars['entity']->num_items;
	if (!isset($num_items)) $num_items = 30;

  $prev_context = get_context();	
  echo display_tagcloud(1, $num_items, 'tags', '', '', elgg_get_page_owner_guid());
  set_context($prev_context);
?>
