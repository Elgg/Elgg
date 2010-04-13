<?php
 
// Latest forum discussion for the group home page

//check to make sure this group forum has been activated
if($vars['entity']->forum_enable != 'no'){
?>
<h3><?php echo elgg_echo('groups:latestdiscussion'); ?></h3>
<?php
	
    $forum = elgg_get_entities_from_annotations(array('types' => 'object', 'subtypes' => 'groupforumtopic', 'annotation_names' => 'group_topic_post', 'container_guid' => $vars['entity']->guid, 'limit' => 6, 'order_by' => 'maxtime desc'));
	
    if($forum){
        foreach($forum as $f){
        	    
                $count_annotations = $f->countAnnotations("group_topic_post");
                 
        	    echo "<div class='entity_listing clearfloat'>";
        	    echo "<div class='entity_listing_icon'>" . elgg_view('profile/icon',array('entity' => $f->getOwnerEntity(), 'size' => 'tiny')) . "</div>";
    	        echo "<div class='entity_listing_info'><p class='entity_title'><a href=\"{$vars['url']}mod/groups/topicposts.php?topic={$f->guid}&group_guid={$vars['entity']->guid}\">" . $f->title . "</a></p>";
    	        echo "<p class='entity_subtext'>".elgg_echo('groups:posts').": " . $count_annotations . "</p></div>";
    	        echo "</div>";
        }
    } else {
		echo "<p class='margin_top'>".elgg_echo("grouptopic:notcreated")."</p>";
    }

}//end of forum active check
?>