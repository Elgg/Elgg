<?php
/**
 * When looking at a users blog, bookmarks, video etc only show 
 * the users name and the tool you are viewing
 *
 * @package Elgg
 * @subpackage Core
 *
 */

$page_owner = elgg_get_page_owner();
$name = elgg_get_page_owner()->name;

// get the object type
$type = $vars['type'];

$title = elgg_echo($type);
$title = $name . "'s " . $type;
?>

<div id="content-header" class="clearfix">
	<?php echo '<div class="content-header-title">' . elgg_view_title($title) . '</div>'; ?>
</div>

