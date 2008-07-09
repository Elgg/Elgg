<?php

	/**
	 * Elgg pagination
	 * 
	 * @package Elgg
	 * @subpackage Core
	 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
	 * @author Curverider Ltd
	 * @copyright Curverider Ltd 2008
	 * @link http://elgg.org/
	 * 
	 */

	if (!isset($vars['offset'])) {
		$offset = 0;
	} else {
		$offset = $vars['offset'];
	}
	if (!isset($vars['limit'])) {
		$limit = 10;
	} else {
		$limit = $vars['limit'];
	}
	if (!isset($vars['count'])) {
		$count = 0;
	} else {
		$count = $vars['count'];
	}
	if (!isset($vars['word'])) {
		$word = "offset";
	} else {
		$word = $vars['word'];
	}
	
	$totalpages = ceil($count / $limit);
	$currentpage = ceil($offset / $limit) + 1;

	$baseurl = preg_replace('/[\&\?]'.$word.'\=[0-9]*/',"",$vars['baseurl']);

?>

<div class="pagination">
	<p>
<?php

	if ($count == 0) {
		
		static $notfounddisplayed;
		if (!isset($notfounddisplayed)) {
			echo elgg_echo("notfound");
			$notfounddisplayed = true;
		}
		
	}

	if ($offset > 0) {
		
		$prevoffset = $offset - $limit;
		if ($prevoffset < 0) $prevoffset = 0;
		
		$prevurl = $baseurl;
		if (substr_count($baseurl,'?')) {
			$prevurl .= "&{$word}=" . $prevoffset;
		} else {
			$prevurl .= "?{$word}=" . $prevoffset;
		}
		
		echo "<a href=\"{$prevurl}\">&laquo; ". elgg_echo("previous") ."</a> ";
		
	}

	$currentpage = round($offset / $limit) + 1;
	$allpages = ceil($count / $limit);
	
	$i = 1;
	$pagesarray = array();
	while ($i <= $allpages && $i <= 3) {
		$pagesarray[] = $i;
		$i++;
	}
	$i = $currentpage - 1;
	while ($i <= $allpages && $i <= ($currentpage + 1)) {
		if ($i > 0 && !in_array($i,$pagesarray))
			$pagesarray[] = $i;
		$i++;
	}
	$i = $allpages - 2;
	while ($i <= $allpages) {
		if ($i > 0 && !in_array($i,$pagesarray))
			$pagesarray[] = $i;
		$i++;
	}
	
	sort($pagesarray);
	
	$prev = 0;
	foreach($pagesarray as $i) {

		if (($i - $prev) > 1) {
			
			echo " ... ";
			
		}
		
		$counturl = $baseurl;
		$curoffset = (($i - 1) * $limit);
		if (substr_count($baseurl,'?')) {
			$counturl .= "&{$word}=" . $curoffset;
		} else {
			$counturl .= "?{$word}=" . $curoffset;
		}
		if ($curoffset != $offset) {
			echo " <a href=\"{$counturl}\">{$i}</a> ";
		} else {
			echo " {$i} ";
		}
		$prev = $i;

	}
	
	if ($offset < ($count - $limit)) {
		
		$nextoffset = $offset + $limit;
		if ($nextoffset >= $count) $nextoffset--;
		
		$nexturl = $baseurl;
		if (substr_count($baseurl,'?')) {
			$nexturl .= "&{$word}=" . $nextoffset;
		} else {
			$nexturl .= "?{$word}=" . $nextoffset;
		}
		
		echo " <a href=\"{$nexturl}\">" . elgg_echo("next") . " &raquo;</a>";
		
	}

?>
	</p>
</div>