<?php

echo elgg_view('footer/analytics');

$js = elgg_get_loaded_js('footer');
foreach ($js as $script) { ?>
	<script src="<?php echo htmlspecialchars($script, ENT_QUOTES, 'UTF-8'); ?>"></script>
<?php
}

$require_config = _elgg_services()->amdConfig->getConfig();
?>
<script>
require.config(<?php echo json_encode($require_config); ?>);
</script>
