<?php
//get blog archives
global $CONFIG;
if (!defined('everyoneblog') && page_owner()) {
	echo "<div class='SidebarBox'>";
	echo "<h3>" . elgg_echo('blog:archive') ."</h3>";
	echo "<div class='ContentWrapper'><div id='Owner_Block_Links'><ul>";
	if ($dates = get_entity_dates('object','blog',page_owner())) {
		foreach($dates as $date) {
			$timestamplow = mktime(0,0,0,substr($date,4,2),1,substr($date,0,4));
			$timestamphigh = mktime(0,0,0,((int) substr($date,4,2)) + 1,1,substr($date,0,4));
			if (!isset($page_owner)) $page_owner = page_owner_entity();
			$link = $CONFIG->wwwroot . 'pg/blog/' . $page_owner->username . '/archive/' . $timestamplow . '/' . $timestamphigh;
			//echo (sprintf(elgg_echo('date:month:'.substr($date,4,2)),substr($date,0,4)),$link,'filter');
			$year = substr($date,0,-2);
			$month = date('F',mktime(0, 0, 0, substr($date,4,2), 1)); //substr($date,4,2);
			$display_date = $month . " " . $year;	
			echo "<li><a href=\"{$link}\">" . $display_date . "</a></li>";
		}								
	}
	echo "</ul></div></div></div>";
}