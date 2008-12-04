<div id="latest_discussion_widget">
<h2><?php echo elgg_echo('groups:latestdiscussion'); ?></h2>
<?php
	
    $forum = get_entities_from_annotations("object", "groupforumtopic", "group_topic_post", "", 0, $vars['entity']->guid, 6, 0, "desc", false);
	
    if($forum){
        foreach($forum as $f){
        	    
                $count_annotations = $f->countAnnotations("group_topic_post");
                 
        	    echo "<div class=\"forum_latest\">";
        	    echo "<div class=\"topic_owner_icon\">" . elgg_view('profile/icon',array('entity' => $f->getOwnerEntity(), 'size' => 'tiny')) . "</div>";
    	        echo "<div class=\"topic_title\"><p><a href=\"{$vars['url']}mod/groups/topicposts.php?topic={$f->guid}&group_guid={$vars['entity']->guid}\">" . $f->title . "</a></p> <p class=\"topic_replies\"><small>".elgg_echo('group:replies').": " . $count_annotations . "</small></p></div>";
    	        	
    	        echo "</div>";
    	        
        }
    }else{
        
        echo elgg_echo("grouptopic:notcreated");
    }
?>
<br class="clearfloat" />
</div>