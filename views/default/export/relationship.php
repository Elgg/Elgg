<?php
/**
 * Elgg relationship export.
 * Displays a relationship using the current view.
 *
 * @package Elgg
 * @subpackage Core
 */

$r = $vars['relationship'];

$e1 = get_entity($r->guid_one);
$e2 = get_entity($r->guid_two);
?>
<p class="margin-none"><?php
	if ($e1) echo "<a href=\"" . $e1->getURL() . "\">GUID:" . $r->guid_one . "</a>"; else echo "GUID:".$r->guid_one;
?>
<b><?php echo $r->relationship; ?></b>
<?php
	if ($e2) echo "<a href=\"" . $e2->getURL() . "\">GUID:" . $r->guid_two . "</a>"; else echo "GUID:".$r->guid_two;
?></p>
