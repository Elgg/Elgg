<?php
/**
 * Elgg bookmark widget view
 * 
 * @package ElggBookmarks
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @author Curverider <info@elgg.com>
 * @copyright Curverider Ltd 2008-2010
 * @link http://elgg.org/
 */

//get the num of shares the user want to display
$num = $vars['entity']->num_display;
		
//if no number has been set, default to 4
if(!$num)
	$num = 4;
			
//grab the users bookmarked items
$bookmarks = elgg_get_entities('object', 'bookmarks',$vars['entity']->owner_guid, "", $num, 0, false);
		
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
		echo "<div class='ContentWrapper bookmarks'>";
		echo "<div class='shares_widget_content'>" . $info . "</div></div>";

	} 

	$user_inbox = $vars['url'] . "pg/bookmarks/" . page_owner_entity()->username;
	if (get_entities('object', 'bookmarks', $vars['entity']->container_guid, '', '', '', true) > $num)      
		echo "<div class='ContentWrapper bookmarks more'><a href=\"{$user_inbox}\">".elgg_echo('bookmarks:read')."</a></div>";

} else {
	echo "<div class='ContentWrapper'>" . elgg_echo("bookmarks:widget:description") . "</div>";
}