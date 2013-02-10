<?php

echo elgg_view('footer/analytics');

$js = elgg_get_loaded_js('footer');
foreach ($js as $script) { ?>
	<script type="text/javascript" src="<?php echo $script; ?>"></script>
<?php
}

?>

<?php

global $CONFIG;

$viewtype = elgg_get_viewtype();

if (elgg_is_simplecache_enabled()) {
	$lastcache = (int)elgg_get_config('lastcache');
	$CONFIG->amd->baseUrl = "/cache/$lastcache/$viewtype/js/";
} else {
	$CONFIG->amd->baseUrl = "/ajax/view/js/";
	$CONFIG->amd->urlArgs = "view=$viewtype";
}

?>
<script>
require.config(<?php echo json_encode($CONFIG->amd); ?>);
</script>
