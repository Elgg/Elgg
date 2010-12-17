<?php
/**
 * Create friends river view has been changed
 */

$query = "UPDATE {$CONFIG->dbprefix}river 
			SET view='river/relationship/friend/create', action_type='create'
			WHERE view='friends/river/create' AND action_type='friend'";
update_data($query);
