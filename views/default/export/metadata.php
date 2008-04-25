<?php
	/**
	 * Elgg metadata export.
	 * Displays a metadata item using the current view.
	 * 
	 * @package Elgg
	 * @subpackage Core
	 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
	 * @author Marcus Povey
	 * @copyright Curverider Ltd 2008
	 * @link http://elgg.org/
	 */

	$m = $vars['metadata'];
?>
<div>
	<table>
		<tr>
		<td><b><?php echo $m->name; ?></b></td>
		<td><?php echo $m->value; ?></td> 
		</tr>
	</table>
</div>