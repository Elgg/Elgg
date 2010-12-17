<?php
/**
 * Create comment river view has been changed
 */

$query = "UPDATE {$CONFIG->dbprefix}river
			SET view='river/annotation/generic_comment/create', action_type='create'
			WHERE view='annotation/annotate' AND action_type='comment'";
update_data($query);

