<?php
/**
 * Administrator sets the categories for the site
 *
 * @package ElggCategories
 */

// Get site categories
$site = elgg_get_site_entity();
$categories = $site->categories;

if (empty($categories)) {
	$categories = array();
}

?>
<div>
	<p><?php echo elgg_echo('categories:explanation'); ?></p>
<?php
	echo elgg_view('input/tags', array('value' => $categories, 'name' => 'categories'));
?>
</div>