<?php
/**
 * Page icon
 *
 * @package ElggPages
 *
 * @uses $vars['entity']
 * @uses $vars['annotation']
 */

$annotation = $vars['annotation'];
$entity = get_entity($annotation->entity_guid);

// Get size
if (!in_array($vars['size'], array('small','medium','large','tiny','master','topbar'))) {
	$vars['size'] = "medium";
}

if (!empty($vars['align'])) {
	$align = " align=\"{$vars['align']}\" ";
} else {
	$align = "";
}

?>

<a href="<?php echo $annotation->getURL(); ?>"><img src="<?php echo $entity->getIcon($vars['size']); ?>" <?php echo $align; ?> /></a>
