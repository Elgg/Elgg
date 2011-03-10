<?php
/**
 * View categories on an entity
 *
 * @uses $vars['entity']
 */

$linkstr = '';
if (isset($vars['entity']) && $vars['entity'] instanceof ElggEntity) {

	$categories = $vars['entity']->universal_categories;
	if (!empty($categories)) {
		if (!is_array($categories)) {
			$categories = array($categories);
		}
		foreach($categories as $category) {
			$link = elgg_get_site_url() . 'categories/list?category=' . urlencode($category);
			if (!empty($linkstr)) {
				$linkstr .= ', ';
			}
			$linkstr .= '<a href="'.$link.'">' . $category . '</a>';
		}
	}

}

if ($linkstr) {
	echo '<p class="elgg-output-categories">' . elgg_echo('categories') . ": $linkstr</p>";
}
