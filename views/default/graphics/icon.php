<?php
/**
 * Generic icon view.
 *
 * @package Elgg
 * @subpackage Core
 * @author Curverider Ltd
 * @link http://elgg.org/
 */

$entity = $vars['entity'];

$sizes = array('small','medium','large','tiny','master','topbar');
// Get size
if (!in_array($vars['size'], $sizes)) {
	$vars['size'] = "medium";
}

// Get any align and js
if (!empty($vars['align'])) {
	$align = " align=\"{$vars['align']}\" ";
} else {
	$align = "";
}


?>
<div class="icon">
<?php
if ($vars['link']) {
	?><a href="<?php echo $vars['link'] ?>"><?php
}
?>
<img src="<?php echo $entity->getIcon($vars['size']); ?>" border="0" <?php echo $align; ?> <?php echo $vars['js']; ?> />
<?php
if ($vars['link']) {
	?></a><?php
}
?>
</div>