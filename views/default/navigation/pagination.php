<?php
/**
 * Elgg pagination
 *
 * @package Elgg
 * @subpackage Core
 *
 * @uses int $vars['offset']
 * @uses int $vars['limit']
 * @uses int $vars['count'] Number of entities.
 * @uses string $vars['word'] Word to use in GET params for the offset
 * @uses string $vars['baseurl'] Base URL to use in links
 */

$offset = (int) elgg_get_array_value('offset', $vars, 0);
// because you can say $vars['limit'] = 0
if (!$limit = (int) elgg_get_array_value('limit', $vars, 10)) {
	$limit = 10;
}
$count = (int) elgg_get_array_value('count', $vars, 0);
$word = elgg_get_array_value('word', $vars, 'offset');
$baseurl = elgg_get_array_value('baseurl', $vars, current_page_url());

$totalpages = ceil($count / $limit);
$currentpage = ceil($offset / $limit) + 1;

//only display if there is content to paginate through or if we already have an offset
if (($count > $limit || $offset > 0) && elgg_get_context() != 'widget') {

	?>

	<div class="pagination clearfix">
	<?php

	if ($offset > 0) {

		$prevoffset = $offset - $limit;
		if ($prevoffset < 0) {
			$prevoffset = 0;
		}

		$prevurl = elgg_http_add_url_query_elements($baseurl, array($word => $prevoffset));

		echo "<a href=\"{$prevurl}\" class='pagination-previous'>&laquo; ". elgg_echo("previous") ."</a> ";
	}

	if ($offset > 0 || $offset < ($count - $limit)) {

		$currentpage = round($offset / $limit) + 1;
		$allpages = ceil($count / $limit);

		$i = 1;
		$pagesarray = array();
		while ($i <= $allpages && $i <= 4) {
			$pagesarray[] = $i;
			$i++;
		}
		$i = $currentpage - 2;
		while ($i <= $allpages && $i <= ($currentpage + 2)) {
			if ($i > 0 && !in_array($i,$pagesarray)) {
				$pagesarray[] = $i;
			}
			$i++;
		}
		$i = $allpages - 3;
		while ($i <= $allpages) {
			if ($i > 0 && !in_array($i,$pagesarray)) {
				$pagesarray[] = $i;
			}
			$i++;
		}

		sort($pagesarray);

		$prev = 0;
		foreach($pagesarray as $i) {
			if (($i - $prev) > 1) {
				echo "<span class='pagination-more'>...</span>";
			}

			$curoffset = (($i - 1) * $limit);
			$counturl = elgg_http_add_url_query_elements($baseurl, array($word => $curoffset));

			if ($curoffset != $offset) {
				echo " <a href=\"{$counturl}\" class='pagination-number'>{$i}</a> ";
			} else {
				echo "<span class='pagination-currentpage'>{$i}</span>";
			}
			$prev = $i;

		}
	}

	if ($offset < ($count - $limit)) {

		$nextoffset = $offset + $limit;
		if ($nextoffset >= $count) {
			$nextoffset--;
		}

		$nexturl = elgg_http_add_url_query_elements($baseurl, array($word => $nextoffset));

		echo " <a href=\"{$nexturl}\" class='pagination-next'>" . elgg_echo("next") . " &raquo;</a>";

	}

	?>
	</div>
	<?php
} // end of pagination check if statement
