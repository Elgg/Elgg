<?php
			
//grab the groups bookmarks 
$bookmarks = elgg_get_entities(array('type' => 'object', 'subtype' => 'bookmarks', 
			'container_guids' => page_owner(), 'limit' => 6));

echo "<div class='group_tool_widget bookmarks'><h3>".elgg_echo('bookmarks:group')."</h3>";
	
if($bookmarks){
	foreach($bookmarks as $b){
			
		//get the owner
		$owner = $b->getOwnerEntity();

		//get the time
		$friendlytime = friendly_time($b->time_created);
		
	    $info = "<div class='entity_listing_icon'>" . elgg_view('profile/icon',array('entity' => $b->getOwnerEntity(), 'size' => 'tiny')) . "</div>";

		//get the bookmark entries body
		$info .= "<div class='entity_listing_info'><p class='entity_title'><a href=\"{$b->address}\">{$b->title}</a></p>";
				
		//get the user details
		$info .= "<p class='entity_subtext'>{$friendlytime}</p>";
		$info .= "</div>";
		//display 
		echo "<div class='entity_listing clearfloat'>" . $info . "</div>";
	} 
} else {
	$create_bookmark = $vars['url'] . "pg/bookmarks/" . page_owner_entity()->username . "/add";
	echo "<p class='margin_top'><a href=\"{$create_bookmark}\">" . elgg_echo("bookmarks:new") . "</a></p>";
}
echo "</div>";