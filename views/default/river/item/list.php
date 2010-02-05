<?php
/**
 *
 * @package Elgg
 * @subpackage Core
 * @author Curverider Ltd
 * @link http://elgg.org/
 *
 */
?>
<div class="river_item_list">
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

		$nav = '';

		if (sizeof($vars['items']) > $vars['limit']) {
			$newoffset = $vars['offset'] + $vars['limit'];
			$nexturl = elgg_http_add_url_query_elements($baseurl, array('offset' => $newoffset));

			$nav .= '<a class="back" href="'.$nexturl.'">&laquo; ' . elgg_echo('previous') . '</a> ';
		}

		if ($vars['offset'] > 0) {
			$newoffset = $vars['offset'] - $vars['limit'];
			if ($newoffset < 0) {
				$newoffset = 0;
			}
			$prevurl = elgg_http_add_url_query_elements($baseurl, array('offset' => $newoffset));

			$nav .= '<a class="forward" href="'.$prevurl.'">' . elgg_echo('next') . ' &raquo;</a> ';
		}

		if (!empty($nav)) {
			echo '<div class="river_pagination"><p>'.$nav.'</p><div class="clearfloat"></div></div>';
		}
	}
?>
</div>