<?php
	/**
	 * Elgg relationship export.
	 * Displays a relationship using the current view.
	 * 
	 * @package Elgg
	 * @subpackage Core
	 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
	 * @author Marcus Povey
	 * @copyright Curverider Ltd 2008
	 * @link http://elgg.org/
	 */

	$r = $vars['relationship'];
	
?>
<div>
	<table>
		<tr>
		<td><?php echo $r->guid_one; ?></td> 
		<td><b><?php echo $r->relationship; ?></b></td>
		<td><?php echo $r->guid_two; ?></td> 
		</tr>
	</table>
</div>