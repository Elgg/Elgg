<?php
/**
 * Some servers don't allow PHP to check the rewrite, so try via AJAX
 */
?>
<script type="text/javascript">
	elgg.installer.rewriteTest(
		'<?php echo $vars['url'];?>',
		'<?php echo elgg_echo('install:check:rewrite:success'); ?>',
		'<?php echo $vars['config']->wwwroot; ?>install.php?step=database'
	);
</script>