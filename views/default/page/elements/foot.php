<?php

echo elgg_view_deprecated('footer/analytics', array(), "Extend page/elements/foot instead", 1.8);

$js = elgg_get_loaded_js('footer');
foreach ($js as $script) { ?>
	<script src="<?php echo htmlspecialchars($script, ENT_QUOTES, 'UTF-8'); ?>"></script>
<?php
}

$deps = _elgg_services()->amdConfig->getDependencies();
?>
<script>
require(<?= json_encode($deps, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT); ?>);
</script>
