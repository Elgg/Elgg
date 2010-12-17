<?php
/**
 * Change the location of the likes river view
 */

$query = "UPDATE {$CONFIG->dbprefix}river
			SET view='river/annotation/likes/create', action_type='create'
			WHERE view='annotation/annotatelike' AND action_type='likes'";
update_data($query);

