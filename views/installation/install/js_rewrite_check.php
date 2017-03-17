<?php
/**
 * Some servers don't allow PHP to check the rewrite, so try via AJAX
 *
 * @uses $vars['url'] URL to test via Ajax (note: not relying on legacy site URL injection)
 */

$args = [
	json_encode($vars['url']),
	json_encode(elgg_echo('install:check:rewrite:success')),
	json_encode(elgg_get_site_url() . 'install.php?step=database')
];
?>
<script>
	elgg.installer.rewriteTest(<?php echo implode(', ', $args) ?>);
</script>
