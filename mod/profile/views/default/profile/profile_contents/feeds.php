<?php
/**
 * Elgg profile feeds page
 */

//$twitter_username = $vars['entity']->twitter;
$page_owner = page_owner();
$feeds = elgg_get_entities(array('types' => 'object', 'subtypes' => 'aggregator_feed_url', 'owner_guids' => $page_owner));

// if the twitter username is empty, then do not show
?>
<div id="profile_content">
<?php
if($feeds){
	echo elgg_view('aggregator/profile',array('feeds'=>$feeds));
}else{
	echo "This user has not added any feeds.";
}
?>
</div>