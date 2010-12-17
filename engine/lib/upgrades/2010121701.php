<?php
/**
 * Create group forum topic river view has been changed
 */

$query = "UPDATE {$CONFIG->dbprefix}river
			SET view='river/object/groupforumtopic/create'
			WHERE view='river/forum/topic/create' AND action_type='create'";
update_data($query);

