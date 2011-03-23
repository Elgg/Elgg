<?php
/**
 * Elgg 1.8-svn upgrade 2011032200
 * admins_like_widgets
 *
 * Give current admins widgets for those pre-1.8
 */

$admins = elgg_get_admins(array('limit' => 0));
foreach ($admins as $admin) {
	elgg_add_admin_widgets('make_admin', 'user', $admin);
}
