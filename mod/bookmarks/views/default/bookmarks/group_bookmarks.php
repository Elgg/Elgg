<?php
			
//grab the groups bookmarks 
$bookmarks = elgg_get_entities(array('type' => 'object', 'subtype' => 'bookmarks', 
			'container_guids' => elgg_get_page_owner_guid(), 'limit' => 6));
?>
<div class="group_tool_widget bookmarks">
<span class="group_widget_link"><a href="<?php echo elgg_get_site_url() . "pg/bookmarks/" . elgg_get_page_owner()->username; ?>"><?php echo elgg_echo('link:view:all')?></a></span>
<h3><?php echo elgg_echo('bookmarks:group') ?></h3>
<?php	
if($bookmarks){
	foreach($bookmarks as $b){
			
		//get the owner
		$owner = $b->getOwnerEntity();

		//get the time
		$friendlytime = elgg_view_friendly_time($b->time_created);
		
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
	$create_bookmark = elgg_get_site_url() . "pg/bookmarks/" . elgg_get_page_owner()->username . "/add";
	echo "<p class='margin_top'><a href=\"{$create_bookmark}\">" . elgg_echo("bookmarks:new") . "</a></p>";
}
echo "</div>";