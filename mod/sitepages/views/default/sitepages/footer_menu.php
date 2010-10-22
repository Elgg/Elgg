<?php
/**
 * Footer view to add links to the semi-static pages.
 *
 * @package SitePages
 */
?>

<div class="footer_toolbar_links">|
<a href="<?php echo $vars['url']; ?>pg/sitepages/read/About/"><?php echo elgg_echo('sitepages:about'); ?></a> |
<a href="<?php echo $vars['url']; ?>pg/sitepages/read/Terms/"><?php echo elgg_echo('sitepages:terms'); ?></a> |
<a href="<?php echo $vars['url']; ?>pg/sitepages/read/Privacy/"><?php echo elgg_echo('sitepages:privacy'); ?></a> |
</div>