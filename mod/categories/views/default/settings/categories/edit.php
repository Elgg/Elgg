<?php
/**
 * Elgg categories plugin settings page
 *
 * @package ElggCategories
 */

// Get site and categories
$site = elgg_get_site_entity();
$categories = $site->categories;

if (empty($categories)) {
	$categories = array();
}

?>
<div class="contentWrapper">
	<p>
		<?php echo elgg_echo('categories:explanation'); ?>
	</p>
	<?php
		echo elgg_view('input/tags', array('value' => $categories, 'internalname' => 'categories'));
	?>
</div>