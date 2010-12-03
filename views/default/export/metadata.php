<?php
/**
 * Elgg metadata export.
 * Displays a metadata item using the current view.
 *
 * @package Elgg
 * @subpackage Core
 */

$m = $vars['metadata'];
$e = get_entity($m->entity_guid);
?>
<p class="margin-none"><?php if ($e) echo "<a href=\"" . $e->getURL() . "\">GUID:{$m->entity_guid}</a>"; else echo "GUID:".$m->entity_guid;
?>: <b><?php echo $m->name; ?></b> <?php echo $m->value; ?></p>