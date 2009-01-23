<?php
	/**
	 * Generic icon view.
	 * 
	 * @package Elgg
	 * @subpackage Core
	 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
	 * @author Curverider Ltd
	 * @copyright Curverider Ltd 2008-2009
	 * @link http://elgg.org/
	 */

	$entity = $vars['entity'];
	
	// Get size
	if (!in_array($vars['size'],array('small','medium','large','tiny','master','topbar')))
		$vars['size'] = "medium";
			
	// Get any align and js
	if (!empty($vars['align'])) {
		$align = " align=\"{$vars['align']}\" ";
	} else {
		$align = "";
	}
	
	
?>
<div class="icon">
<?php if ($vars['link']) { ?><a href="<?php echo $vars['link'] ?>"><?php } ?>
<img src="<?php echo $entity->getIcon($vars['size']); ?>" border="0" <?php echo $align; ?> <?php echo $vars['js']; ?> />
<?php if ($vars['link']) { ?></a><?php } ?>
</div>