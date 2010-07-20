<?php

$categories = $vars['config']->site->categories;

if ($categories) {
	if (!is_array($categories)) {
		$categories = array($categories);
	}

	if (!empty($vars['subtype'])) {
		$flag = array();
		$owner_guid = '';
		if (isset($vars['owner_guid'])) {
			$owner_guid = (int) $vars['owner_guid'];
		}

		elgg_register_tag_metadata_name('universal_categories');
		$params = array(
			'threshold' => 1,
			'limit' => 999,
			'tag_names' => array('universal_categories'),
			'types' => 'object',
			'subtypes' => $vars['subtype'],
			'owner_guid' => $owner_guid,
		);
		$cats = elgg_get_tags($params);		
		if ($cats) {
			foreach($cats as $cat) {
				$flag[] = $cat->tag;
			}
		}

	} else {
		$flag = null;
	}

	if (is_null($flag) || !empty($flag)) {

		?>

<h2><?php echo elgg_echo('categories'); ?></h2>
<div class="categories">
			<?php

			$catstring = '';
			if (!empty($categories)) {
				foreach($categories as $category) {
					if (is_null($flag) || (is_array($flag) && in_array($category,$flag))) {
						$catstring .= '<li><a href="'.$vars['baseurl'].urlencode($category).'">'. $category .'</a></li>';
					}
				}
			}
			if (!empty($catstring)) {
				echo "<ul>{$catstring}</ul>";
			}

			?>
</div>
		<?php
	}

}
