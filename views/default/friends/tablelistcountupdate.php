<?php

	/**
	 * Elgg friends picker count updater
	 * Updates the friends count on a collection
	 * 
	 * @package Elgg
	 * @subpackage Core
	 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
	 * @author Curverider Ltd
	 * @copyright Curverider Ltd 2008-2009
	 * @link http://elgg.org/
	 * 
	 * @uses $vars['count'] The count
	 * @uses $vars['friendspicker'] The friendspicker counter number
	 */

?>

<script language="text/javascript">
	$("#friends_membership_count<?php echo $vars['friendspicker']; ?>").html("<?php echo $vars['count']; ?>");
</script>