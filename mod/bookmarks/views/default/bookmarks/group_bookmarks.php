<?php
			
//grab the groups bookmarks 
//@todo adjust so it actually grabs the group bookmarks rather than the users
$bookmarks = get_entities('object', 'bookmarks',$vars['entity']->owner_guid, "", 6, 0, false);

echo "<div class='group_tool_widget'><h3>".elgg_echo('bookmarks:group')."</h3>";
	
if($bookmarks){
	foreach($bookmarks as $b){
			
		//get the owner
		$owner = $b->getOwnerEntity();

		//get the time
		$friendlytime = friendly_time($b->time_created);

		//get the bookmark title
		$info = "<div class='river_object_bookmarks_create'><p class=\"shares_title\"><a href=\"{$b->address}\">{$b->title}</a></p></div>";
				
		//get the user details
		$info .= "<p class=\"shares_timestamp\"><small>{$friendlytime} ";

		//get the bookmark description
		if($s->description)
			$info .= "<a href=\"javascript:void(0);\" class=\"share_more_info\">".elgg_echo('bookmarks:more')."</a></small></p><div class=\"share_desc\"><p>{$s->description}</p></div>";
		else 
			$info .= "</small></p>";
	
		//display 
		echo "<div class='shares_widget_content'>" . $info . "</div>";
	} 
} else {
	echo "<p class='margin_top'>" . elgg_echo("bookmarks:none") . "</p>";
}
echo "</div>";