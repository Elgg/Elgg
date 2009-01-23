<?php
	/**
	 * Elgg metadata export.
	 * Displays a metadata item using the current view.
	 * 
	 * @package Elgg
	 * @subpackage Core
	 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
	 * @author Curverider Ltd
	 * @copyright Curverider Ltd 2008-2009
	 * @link http://elgg.org/
	 */

	$m = $vars['metadata'];
	$e = get_entity($m->entity_guid);
?>
<div>
	<p><?php if ($e) echo "<a href=\"" . $e->getURL() . "\">GUID:{$m->entity_guid}</a>"; else echo "GUID:".$m->entity_guid;
	?>: <b><?php echo $m->name; ?></b> <?php echo $m->value; ?></p>
</div>