<?php

echo elgg_view('footer/analytics');

$js = elgg_get_loaded_js('footer');
foreach ($js as $script) { ?>
	<script src="<?php echo htmlspecialchars($script, ENT_QUOTES, 'UTF-8'); ?>"></script>
<?php
}

$deps = _elgg_services()->amdConfig->getDependencies();
?>
<script>
require(<?php echo json_encode($deps); ?>);
</script>
