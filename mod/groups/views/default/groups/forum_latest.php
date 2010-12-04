<?php
 
// Latest forum discussion for the group home page

//check to make sure this group forum has been activated
if($vars['entity']->forum_enable != 'no'){
?>
<span class="group_widget_link"><a href="<?php echo elgg_get_site_url() . "pg/groups/forum/" . elgg_get_page_owner_guid(); ?>"><?php echo elgg_echo('link:view:all')?></a></span>
<h3><?php echo elgg_echo('groups:latestdiscussion'); ?></h3>
<?php
	
    $forum = elgg_get_entities(array('types' => 'object', 'subtypes' => 'groupforumtopic', 'container_guid' => $vars['entity']->guid, 'limit' => 6));
	
    if($forum){
        foreach($forum as $f){
        	    
                $count_annotations = $f->countAnnotations("generic_comment");
                 
        	    echo "<div class='entity-listing clearfix'>";
        	    echo "<div class='entity-listing-icon'>" . elgg_view('profile/icon',array('entity' => $f->getOwnerEntity(), 'size' => 'tiny')) . "</div>";
    	        echo "<div class='entity-listing-info'><p class='entity-title'><a href=\"".elgg_get_site_url()."mod/groups/topicposts.php?topic={$f->guid}&group_guid={$vars['entity']->guid}\">" . $f->title . "</a></p>";
    	        echo "<p class='entity-subtext'>".elgg_echo('comments').": " . $count_annotations . "</p></div>";
    	        echo "</div>";
        }
    } else {
	    if(elgg_get_page_owner()->isMember(get_loggedin_user())){
			$create_discussion = elgg_get_site_url() . "mod/groups/addtopic.php?group_guid=" . elgg_get_page_owner_guid();
			echo "<p class='margin-top'><a href=\"{$create_discussion}\">".elgg_echo("groups:addtopic")."</a></p>";
		}else{
			echo "<p class='margin-top'>". elgg_echo("grouptopic:notcreated") . "</p>";
		}
    }

}//end of forum active check