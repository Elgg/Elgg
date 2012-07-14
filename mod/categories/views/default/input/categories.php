<?php
/**
 * Categories input view
 *
 * @package ElggCategories
 *
 * @uses $vars['entity'] The entity being edited or created
 */

if (isset($vars['entity']) && $vars['entity'] instanceof ElggEntity) {
	$selected_categories = $vars['entity']->universal_categories;
}

// use sticky values if set
if (isset($vars['universal_categories_list'])) {
	$selected_categories = $vars['universal_categories_list'];
}

$categories = elgg_get_site_entity()->categories;
if (empty($categories)) {
	$categories = array();
}
if (empty($selected_categories)) {
	$selected_categories = array();
}

if (!empty($categories)) {
	if (!is_array($categories)) {
		$categories = array($categories);
	}

	// checkboxes want Label => value, so in our case we need category => category
	$categories = array_flip($categories);
	array_walk($categories, create_function('&$v, $k', '$v = $k;'));

	?>

<div class="categories">
	<label><?php echo elgg_echo('categories'); ?></label><br />
	<?php
		echo elgg_view('input/checkboxes', array(
			'options' => $categories,
			'value' => $selected_categories,
			'name' => 'universal_categories_list',
			'align' => 'horizontal',
		));

	?>
	<input type="hidden" name="universal_category_marker" value="on" />
</div>

	<?php

} else {
	echo '<input type="hidden" name="universal_category_marker" value="on" />';
}
