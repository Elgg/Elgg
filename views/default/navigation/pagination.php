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
	
	$totalpages = ceil($count / $limit);
	$currentpage = ceil($offset / $limit) + 1;

	$baseurl = preg_replace('/[\&\?]offset\=[0-9]*/',"",$vars['baseurl']);

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
			$prevurl .= "&offset=" . $prevoffset;
		} else {
			$prevurl .= "?offset=" . $prevoffset;
		}
		
		echo "<a href=\"{$prevurl}\">&lt;&lt; ". elgg_echo("previous") ."</a> ";
		
	}

	if ($offset < ($count - $limit)) {
		
		$nextoffset = $offset + $limit;
		if ($nextoffset >= $count) $nextoffset--;
		
		$nexturl = $baseurl;
		if (substr_count($baseurl,'?')) {
			$nexturl .= "&offset=" . $nextoffset;
		} else {
			$nexturl .= "?offset=" . $nextoffset;
		}
		
		echo " <a href=\"{$nexturl}\">" . elgg_echo("next") . " &gt;&gt;</a>";
		
	}

?>
	</p>
</div>