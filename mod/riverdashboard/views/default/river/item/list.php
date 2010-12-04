<?php

if (isset($vars['items']) && is_array($vars['items'])) {

	$i = 0;
	if (!empty($vars['items'])) {
		foreach($vars['items'] as $item) {
			echo elgg_view_river_item($item);
			$i++;
			if ($i >= $vars['limit']) {
				break;
			}
		}
	}
}

if ($vars['pagination'] !== false) {
	$baseurl = $_SERVER['REQUEST_URI'];
	$baseurl = preg_replace('/[\&\?]offset\=[0-9]*/',"",$baseurl);

	$nav = '';

	if (sizeof($vars['items']) > $vars['limit']) {
		$newoffset = $vars['offset'] + $vars['limit'];
		$urladdition = 'offset='.$newoffset;
		if (substr_count($baseurl,'?')) {
			$nexturl = $baseurl . '&' . $urladdition;
		} else {
			$nexturl=$baseurl . '?' . $urladdition;
		}

		$nav .= '<a class="pagination-previous" href="'.$nexturl.'">&laquo; ' . elgg_echo('previous') . '</a> ';
	}

	if ($vars['offset'] > 0) {
		$newoffset = $vars['offset'] - $vars['limit'];
		if ($newoffset < 0) {
			$newoffset = 0;
		}
		$urladdition = 'offset='.$newoffset;
		if (substr_count($baseurl,'?')) {
			$prevurl=$baseurl . '&' . $urladdition;
		} else {
			$prevurl=$baseurl . '?' . $urladdition;
		}

		$nav .= '<a class="pagination-next" href="'.$prevurl.'">' . elgg_echo('next') . ' &raquo;</a> ';
	}

	if (!empty($nav)) {
		echo '<div class="pagination clearfix">'.$nav.'</div>';
	}
}

?>

<script type="text/javascript">

// pull in extra comments and likes with ajax
$(function() {

});
</script>