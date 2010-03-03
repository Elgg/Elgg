<?php

	$linkstr = '';
	if (isset($vars['entity']) && $vars['entity'] instanceof ElggEntity) {
		
		$categories = $vars['entity']->universal_categories;
		if (!empty($categories)) {
			if (!is_array($categories)) $categories = array($categories);
			foreach($categories as $category) {
				$link = $vars['url'] . 'search?tagtype=universal_categories&tag=' . urlencode($category);
				if (!empty($linkstr)) $linkstr .= ', ';
				$linkstr .= '<a href="'.$link.'">' . $category . '</a>';
			}
		}
		
	}
	echo $linkstr;

?>