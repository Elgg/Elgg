<?php

	/**
	 * Elgg list view switcher
	 * 
	 * @package Elgg
	 * @subpackage Core
	 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
	 * @author Curverider Ltd
	 * @copyright Curverider Ltd 2008-2009
	 * @link http://elgg.org/
	 */


		$baseurl = preg_replace('/[\&\?]search\_viewtype\=[A-Za-z0-9]*/',"",$vars['baseurl']);
		
		if ($vars['viewtype'] == "list") {
			$viewtype = "gallery";
		} else {
			$viewtype = "list";
		}
		
		if (substr_count($baseurl,'?')) {
			$baseurl .= "&search_viewtype=" . $viewtype;
		} else {
			$baseurl .= "?search_viewtype=" . $viewtype;
		}

?>

	<div class="contentWrapper">
		<?php echo elgg_echo("viewtype:change") ?>:
		<a href="<?php echo $baseurl; ?>"><?php echo elgg_echo("viewtype:{$viewtype}"); ?></a>
	</div>