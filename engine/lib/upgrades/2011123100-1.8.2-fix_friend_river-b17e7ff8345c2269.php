<?php
/**
 * Elgg 1.8.2 upgrade 2011123100
 * fix_friend_river
 *
 * Action type was incorrect due to previoud friends river upgrade
 */

$query = "UPDATE {$CONFIG->dbprefix}river
			SET action_type='friend'
			WHERE view='river/relationship/friend/create' AND action_type='create'";
update_data($query);
