<?php
/**
 * Check for new activity.
 * Outputs # of new activity items since $_GET['last_checked'] time
 */

// Load Elgg engine will not include plugins
require_once(dirname(dirname(dirname(dirname(__FILE__)))) . "/engine/start.php");

// check for last checked time
if (!$seconds_passed = get_input('seconds_passed', 0)) {
	echo '';
	exit;
}

$last_reload = time() - $seconds_passed;

// This entire system is driven by the river table.
// There is no core interface to simply grab the number of entries in the table.
// In order for something to count as an update, you must put a call to add_river-item().
// @todo Remove this when elgg_get_river-items() supports 1.7-style API and count => TRUE and not group by object_guid

// river table does not have columns expected by get_access_sql_suffix so we modify its output
$access = str_replace("and enabled='yes'", '', str_replace('owner_guid', 'subject_guid', riverdashboard_get_access_sql_suffix('r', 'e')));

$q = "SELECT COUNT(id) as all_activity FROM {$CONFIG->dbprefix}river r, {$CONFIG->dbprefix}entities e
	WHERE r.posted > $last_reload AND r.object_guid = e.guid AND ($access)";

if ($d = get_data($q)) {
	$all_activity = $d[0]->all_activity;
} else {
	$all_activity = 0;
}

if ($all_activity > 0) {
	$s = ($all_activity == 1) ? '' : 's';
	echo "<a href='' onClick=\"window.location.reload();\" class='update-link'>$all_activity update$s!</a>";
?>
	<script type="text/javascript">
		$(document).ready(function(){

			var pageTitleSubstring;
			var stringStartPosition = document.title.indexOf("]");

			if (stringStartPosition == -1) { // we haven't already altered page title
				pageTitleSubstring = document.title;
			} else { // we previously prepended to page title, need to remove it first
				pageTitleSubstring = document.title.substring( (stringStartPosition+2) );
			}

			document.title = "[<?php echo $all_activity; ?> update<?php echo $s; ?>] "+pageTitleSubstring;
		});
	</script>

<?php
} else {
	echo '';
	exit;
}
