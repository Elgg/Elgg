<?php
/**
 * Elgg 1.8.0.1 upgrade 2011092500
 * forum_reply_river_view
 *
 * The forum reply river view is in a new location in Elgg 1.8
 */

$query = "UPDATE {$CONFIG->dbprefix}river SET view='river/annotation/group_topic_post/reply',
			action_type='reply'
			WHERE view='river/forum/create' AND action_type='create'";
update_data($query);
