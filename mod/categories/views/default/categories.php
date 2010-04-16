<?php

/**
 * Elgg categories plugin
 *
 * @package ElggCategories
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @author Curverider Ltd <info@elgg.com>
 * @copyright Curverider Ltd 2008-2010
 * @link http://elgg.com/
 */

if (isset($vars['entity']) && $vars['entity'] instanceof ElggEntity) {
	$selected_categories = $vars['entity']->universal_categories;
}
$categories = $vars['config']->site->categories;
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

	?>

<div id="content_area_user_title"><h2 class="categoriestitle"><?php echo elgg_echo('categories'); ?></h2></div>
<div class="categories">
	<p>

			<?php

			echo elgg_view('input/checkboxes',array(
				'options' => $categories,
				'value' => $selected_categories,
				'internalname' => 'universal_categories_list'
				));

			?>
		<input type="hidden" name="universal_category_marker" value="on" />
	</p>
</div>

	<?php

} else {
	echo '<input type="hidden" name="universal_category_marker" value="on" />';
}
