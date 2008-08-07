<h2>Forum latest</h2>
<?php

//get the latest from the group files
// Display them
	   // list_entities_from_annotations($entity_type = "", $entity_subtype = "", $name = "", $value = "", $limit = 10, $owner_guid = 0, $group_guid = 0, $asc = false, $fullview = true) {
		
		set_context('search');
	   // $forum_topics = list_entities_from_annotations("object", "groupforumtopic", "group_topic_post", "", 5, 0, 3, false, true);
	    set_context('forums');
	    
	    echo $forum_topics;
	    
?>