<?php
	/**
	 * Elgg OpenDD aggregator
	 * 
	 * @package ElggOpenDD
	 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
	 * @author Marcus Povey
	 * @copyright Curverider Ltd 2008
	 * @link http://elgg.com/
	 */

	$group = $vars['entity'];
	
	// Get size
	if (!in_array($vars['size'],array('small','medium','large','tiny','master','topbar')))
		$vars['size'] = "medium";
			
	// Get any align and js
	if (!empty($vars['align'])) {
		$align = " align=\"{$vars['align']}\" ";
	} else {
		$align = "";
	}
	
	if ($icontime = $vars['entity']->icontime) {
		$icontime = "{$icontime}";
	} else {
		$icontime = "default";
	}
	
	
?>

<div class="groupicon">
<a href="<?php echo $vars['entity']->getURL(); ?>" class="icon" ><img src="<?php echo $vars['url']; ?>mod/opendd/graphics/default<?php echo $vars['size']; ?>" border="0" <?php echo $align; ?> <?php echo $vars['js']; ?> /></a>
</div>