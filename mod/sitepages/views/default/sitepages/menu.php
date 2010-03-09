<?php
/**
 * Displays the tabbed menu for editing site pages.
 *
 * @package SitePages
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @author Curverider Ltd
 * @copyright Curverider Ltd 2008-2010
 * @link http://elgg.org/
 */

$page_type = $vars['page_type'];
$url = $vars['url'] . 'pg/sitepages/edit/';

?>

<div class="elgg_horizontal_tabbed_nav">
<ul>
	<li <?php if($page_type == 'front') echo "class = 'selected'"; ?>><a href="<?php echo $url; ?>front"><?php echo elgg_echo('sitepages:frontpage'); ?></a></li>
	<li <?php if($page_type == 'about') echo "class = 'selected'"; ?>><a href="<?php echo $url; ?>about"><?php echo elgg_echo('sitepages:about'); ?></a></li>
	<li <?php if($page_type == 'terms') echo "class = 'selected'"; ?>><a href="<?php echo $url; ?>terms"><?php echo elgg_echo('sitepages:terms'); ?></a></li>
	<li <?php if($page_type == 'privacy') echo "class = 'selected'"; ?>><a href="<?php echo $url; ?>privacy"><?php echo elgg_echo('sitepages:privacy'); ?></a></li>
	<li <?php if($page_type == 'seo') echo "class = 'selected'"; ?>><a href="<?php echo $url; ?>seo"><?php echo elgg_echo('sitepages:seo'); ?></a></li>
</ul>
</div>