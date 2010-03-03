<?php
 
    // Latest forum discussion for the group home page

    //check to make sure this group forum has been activated
    if($vars['entity']->forum_enable != 'no'){

?>

<div class="contentWrapper">
<h2><?php echo elgg_echo('groups:latestdiscussion'); ?></h2>
<?php
	
    $forum = elgg_get_entities_from_annotations(array('types' => 'object', 'subtypes' => 'groupforumtopic', 'annotation_names' => 'group_topic_post', 'container_guid' => $vars['entity']->guid, 'limit' => 4, 'order_by' => 'maxtime desc'));
	
    if($forum){
        foreach($forum as $f){
        	    
                $count_annotations = $f->countAnnotations("group_topic_post");
                 
        	    echo "<div class=\"forum_latest\">";
        	    echo "<div class=\"topic_owner_icon\">" . elgg_view('profile/icon',array('entity' => $f->getOwnerEntity(), 'size' => 'tiny', 'override' => true)) . "</div>";
    	        echo "<div class=\"topic_title\"><p><a href=\"{$vars['url']}mod/groups/topicposts.php?topic={$f->guid}&group_guid={$vars['entity']->guid}\">" . $f->title . "</a></p> <p class=\"topic_replies\"><small>".elgg_echo('groups:posts').": " . $count_annotations . "</small></p></div>";
    	        	
    	        echo "</div>";
    	        
        }
    } else {
		echo "<div class=\"forum_latest\">";
		echo elgg_echo("grouptopic:notcreated");
		echo "</div>";
    }
?>
<div class="clearfloat" /></div>
</div>
<?php
	}//end of forum active check
?>