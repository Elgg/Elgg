<?php
/**
 * Elgg External pages menu
 * 
 * @package ElggExpages
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @author Curverider Ltd <info@elgg.com>
 * @copyright Curverider Ltd 2008-2010
 * @link http://elgg.com/
 * 
 */

$type = $vars['type'];

$url = $vars['url'] . "pg/expages/index.php?type=";
?>

<div class="elgg_horizontal_tabbed_nav">
<ul>
	<li <?php if($type == 'about')   echo "class = 'selected'"; ?>><a href="<?php echo $url; ?>about"><?php echo elgg_echo('expages:about'); ?></a></li>
	<li <?php if($type == 'terms')   echo "class = 'selected'"; ?>><a href="<?php echo $url; ?>terms"><?php echo elgg_echo('expages:terms'); ?></a></li>
	<li <?php if($type == 'privacy') echo "class = 'selected'"; ?>><a href="<?php echo $url; ?>privacy"><?php echo elgg_echo('expages:privacy'); ?></a></li>
</ul>
</div>